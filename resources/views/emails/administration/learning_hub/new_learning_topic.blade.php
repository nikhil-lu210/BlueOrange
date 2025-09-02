<x-mail::message>
# New Learning Topic Available

Hello {{ $user->name }},

A new learning topic has been created by {{ $data->creator->employee->alias_name ?? $data->creator->name }}.

**Topic:** {{ $data->title }}

**Description:**
{{ $data->description }}

<x-mail::button :url="route('administration.learning_hub.show', ['learning_topic' => $data])">
View Learning Topic
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
