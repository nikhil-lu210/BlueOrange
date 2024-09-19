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
