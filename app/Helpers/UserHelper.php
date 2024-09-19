<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

if (!function_exists('show_role')) {

    /**
     * Get specific column value from Role by ID using pluck method.
     *
     * @param int $role_id Role ID
     * @param string $column Column name
     * @return mixed Column value or null if Role not found
     */
    function show_role(int $role_id, string $column = 'name')
    {
        $value = Role::where('id', $role_id)->pluck($column)->first();

        return $value ?? null;
    }
}

if (!function_exists('show_user_data')) {

    /**
     * Get specific column value from User by ID using pluck method.
     *
     * @param int $user_id User ID
     * @param string $column Column name
     * @return mixed Column value or null if user not found
     */
    function show_user_data(int $user_id, string $column)
    {
        $value = User::where('id', $user_id)->pluck($column)->first();

        return $value ?? null;
    }
}

if (!function_exists('profile_name_pic')) {

    /**
     * Get the initials (first letter of first name and last name) for a given user.
     *
     * @param  int  $userId  The user ID.
     * @return string  The initials.
     */
    function profile_name_pic($userId)
    {
        // Fetch the user from the database using the user ID
        $user = User::find($userId);

        // Check if user exists
        if (!$user) {
            return '';
        }

        // Get first and last names, assuming they are not empty
        $firstName = $user->first_name;
        $lastName = $user->last_name;

        // Extract the first letter from each part of the first and last name
        $firstInitial = $firstName ? strtoupper(substr($firstName, 0, 1)) : '';
        $lastInitial = $lastName ? strtoupper(substr($lastName, 0, 1)) : '';

        // Return the concatenated initials
        return $firstInitial . $lastInitial;
    }
}



if (!function_exists('show_user_name_and_avatar')) {

    /**
     * Display the user name and avatar in a consistent layout.
     *
     * @param  \App\Models\User  $user
     * @return string  The HTML output for user name and avatar.
     */
    function show_user_name_and_avatar($user)
    {
        // Determine the avatar URL or initials
        $avatarHtml = '';
        if ($user->hasMedia('avatar')) {
            $avatarUrl = $user->getFirstMediaUrl('avatar', 'thumb');
            $avatarHtml = '<img src="' . $avatarUrl . '" alt="' . htmlspecialchars($user->name) . ' Avatar" class="rounded-circle">';
        } else {
            $initials = profile_name_pic($user->id);
            $avatarHtml = '<span class="avatar-initial rounded-circle bg-label-hover-dark text-bold">' . $initials . '</span>';
        }

        // Generate the full name and alias display
        $nameHtml = '<a href="' . route('administration.settings.user.show.profile', ['user' => $user]) . '" target="_blank" class="text-bold">' . htmlspecialchars($user->name) . '</a>';
        $aliasName = optional($user->employee)->alias_name ?? '';
        $roleName = $user->roles[0]->name ?? '';

        // Construct the HTML output
        $html = '
        <div class="d-flex justify-content-start align-items-center user-name">
            <div class="avatar-wrapper">
                <div class="avatar me-2">
                    <a href="' . route('administration.settings.user.show.profile', ['user' => $user]) . '">
                        ' . $avatarHtml . '
                    </a>
                </div>
            </div>
            <div class="d-flex flex-column">
                ' . $nameHtml . '
                <small class="text-bold text-dark">' . htmlspecialchars($aliasName) . '</small>
                <small class="text-truncate text-muted">' . htmlspecialchars($roleName) . '</small>
            </div>
        </div>';

        return $html;
    }
}