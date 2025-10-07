<?php

namespace App\Http\Controllers\Administration\Settings\System\AppSetting;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\Translation\Translation;
use App\Services\Administration\Translator\TranslationManagementService;
use App\Http\Requests\Administration\Settings\System\AppSetting\Translation\TranslationStoreRequest;
use App\Http\Requests\Administration\Settings\System\AppSetting\Translation\TranslationUpdateRequest;

class TranslationController extends Controller
{
    protected TranslationManagementService $translationService;

    public function __construct(TranslationManagementService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->get('search');
        $locale = request()->get('locale');
        $translations = $this->translationService->getPaginatedTranslations(10, $search, $locale);
        $localeDetails = $this->translationService->getAllLocaleDetails();

        return view('administration.settings.system.app_settings.translation.index', compact(
            'translations',
            'localeDetails'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $localeDetails = $this->translationService->getAllLocaleDetails();

        return view('administration.settings.system.app_settings.translation.create', compact('localeDetails'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TranslationStoreRequest $request)
    {
        try {
            $translation = $this->translationService->createTranslation($request->validated());

            toast('Translation has been created successfully.', 'success');

            return redirect()->route('administration.settings.system.app_setting.translation.index');
        } catch (Exception $e) {
            toast('Failed to create translation: ' . $e->getMessage(), 'error');

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Translation $translation)
    {
        return view('administration.settings.system.app_settings.translation.show', compact('translation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Translation $translation)
    {
        $localeDetails = $this->translationService->getAllLocaleDetails();

        return view('administration.settings.system.app_settings.translation.edit', compact('translation', 'localeDetails'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TranslationUpdateRequest $request, Translation $translation)
    {
        try {
            $this->translationService->updateTranslation($translation, $request->validated());

            toast('Translation has been updated successfully.', 'success');

            return redirect()->back();
        } catch (Exception $e) {
            toast('Failed to update translation: ' . $e->getMessage(), 'error');

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Translation $translation)
    {
        try {
            $this->translationService->deleteTranslation($translation);

            toast('Translation has been deleted successfully.', 'success');

            return redirect()->back();
        } catch (Exception $e) {
            toast('Failed to delete translation: ' . $e->getMessage(), 'error');

            return redirect()->back();
        }
    }

    /**
     * Switch application language
     */
    public function switchLanguage(string $lang)
    {
        // Get the list of valid active locales from the translation config
        $supportedLocales = config('translation.supported_locales', []);
        
        // Check if the locale exists and is enabled
        if (!isset($supportedLocales[$lang]) || !$supportedLocales[$lang]['will_use']) {
            abort(400, 'Invalid or disabled language');
        }

        // Set local language in session
        session(['localization' => $lang]);

        return redirect()->back();
    }
}
