<?php

use App\Models\User;
use App\Models\User\Employee\Employee;
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

if (!function_exists('show_employee_data')) {

    /**
     * Get specific column value from Employee by user_id using pluck method.
     *
     * @param int $user_id User ID
     * @param string $column Column name
     * @return mixed Column value or null if user not found
     */
    function show_employee_data(int $user_id, string $column)
    {
        $value = Employee::where('user_id', $user_id)->pluck($column)->first();

        return $value ?? null;
    }
}


if (!function_exists('profile_name_pic')) {

    /**
     * Get the initials (first letter of first name and last name) for a given user.
     *
     * @param  User  $user  The User object.
     * @return string  The initials.
     */
    function profile_name_pic($user)
    {
        // Check if user exists
        if (!$user) {
            return '';
        }

        // Get first and last names
        $firstName = $user->first_name;
        $lastName = $user->last_name;

        // Initialize initials
        $initials = '';

        // Check if first name and last name exist, otherwise take initials from name
        if ($firstName && $lastName) {
            $firstInitial = strtoupper(substr($firstName, 0, 1));
            $lastInitial = strtoupper(substr($lastName, 0, 1));
            $initials = $firstInitial . $lastInitial;
        } else {
            // Fallback to taking two letters from the name
            $fullName = $user->name ?? '';
            $initials = strtoupper(substr($fullName, 0, 2)); // Take the first two letters
        }

        // Return the initials
        return $initials;
    }
}



if (!function_exists('show_user_name_and_avatar')) {

    /**
     * Display the user name, alias, avatar, and role in a consistent layout.
     *
     * @param  \App\Models\User  $user
     * @param  bool  $name   Whether to display the user's name.
     * @param  bool  $alias  Whether to display the user's alias name.
     * @param  bool  $avatar Whether to display the user's avatar.
     * @param  bool  $role   Whether to display the user's role.
     * @return string  The HTML output for user name, alias, avatar, and role.
     */
    function show_user_name_and_avatar($user, $name = true, $alias = true, $avatar = true, $role = true)
    {
        $avatarHtml = '';
        if ($avatar) {
            if ($user->hasMedia('avatar')) {
                $avatarUrl = $user->getFirstMediaUrl('avatar', 'thumb');
                $avatarHtml = '<img src="' . $avatarUrl . '" alt="' . htmlspecialchars($user->name) . ' Avatar" class="rounded-circle">';
            } else {
                $initials = profile_name_pic($user);
                $avatarHtml = '<span class="avatar-initial rounded-circle bg-label-hover-dark text-bold">' . $initials . '</span>';
            }
            $avatarHtml = '
            <div class="avatar-wrapper">
                <div class="avatar me-2">
                    <a href="' . route('administration.settings.user.show.profile', ['user' => $user]) . '">
                        ' . $avatarHtml . '
                    </a>
                </div>
            </div>';
        }

        $nameHtml = '';
        if ($name) {
            $nameHtml = '<small class="text-bold text-dark">' . htmlspecialchars($user->name) . '</small>';
        }

        $aliasNameHtml = '';
        if ($alias) {
            $aliasName = optional($user->employee)->alias_name ?? '';
            $aliasNameHtml = '<a href="' . route('administration.settings.user.show.profile', ['user' => $user]) . '" target="_blank" class="text-bold">' . htmlspecialchars($aliasName) . '</a>';
        }

        $roleHtml = '';
        if ($role) {
            $roleName = $user->role->name ?? '';
            $roleHtml = '<small class="text-truncate text-muted">' . htmlspecialchars($roleName) . '</small>';
        }

        // Construct the final HTML output
        $html = '
        <div class="d-flex justify-content-start align-items-center user-name">
            ' . $avatarHtml . '
            <div class="d-flex flex-column">
                ' . $aliasNameHtml . '
                ' . $nameHtml . '
                ' . $roleHtml . '
            </div>
        </div>';

        return $html;
    }
}



if (!function_exists('get_employee_name')) {

    /**
     * Get the employee alias name if available, otherwise return the user's full name.
     * If the alias name is present, return it in the format "alias_name (full_name)".
     *
     * @param  \App\Models\User  $user
     * @return string  The formatted name.
     */
    function get_employee_name($user)
    {
        // Check if the user has an employee and alias_name is present
        $aliasName = optional($user->employee)->alias_name;

        // Return the formatted name based on the presence of alias_name
        if (is_null($aliasName)) {
            return $user->name; // Return the user's full name if alias_name is null
        }

        return $aliasName . ' (' . $user->name . ')'; // Return alias_name with full name in parentheses
    }
}
