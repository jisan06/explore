<script setup>
import {Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
const {users, auth} = usePage().props;
const deleteUser = async (id) => {
    if (confirm('Are you sure you want to delete ?')) {
        try {
             Inertia.delete(route('users.destroy', id));
        } catch (error) {
            console.error('Error deleting item:', error);
            // Handle error as needed
        }
    }
};
</script>
<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="mb-4 flex justify-between">
                            <div class="flex gap-4">
                                <Link
                                    class="bg-green-500 text-white p-3 inline-block"
                                    :href="route('users.index')"
                                >
                                    Active Users
                                </Link>
                                <Link
                                    class="bg-red-400 text-white p-3 inline-block"
                                    :href="route('users.trashed')"
                                >
                                    Trash Users
                                </Link>
                            </div>
                            <div>
                                <Link
                                    class="bg-blue-500 text-white p-3 inline-block"
                                    :href="route('users.create')"
                                >
                                    Create User
                                </Link>
                            </div>
                        </div>
                        <div>
                            <h3>Active Users</h3>
                            <table class="text-center w-full" v-if="users.length">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="user in users">
                                    <td>{{user.fullname}}</td>
                                    <td>{{user.username}}</td>
                                    <td>{{user.email}}</td>
                                    <td class="w-[30%]">
                                        <div class="flex gap-1 justify-center">
                                            <Link
                                                class="p-2 bg-amber-500 text-white"
                                                :href="route('users.edit', user.id)"
                                            >
                                                Edit
                                            </Link>
                                            <Link
                                                class="p-2 bg-green-500 text-white"
                                                :href="route('users.show', user.id)"
                                            >
                                                View
                                            </Link>
                                            <button
                                                class="p-2 bg-red-500 text-white"
                                                @click=deleteUser(user.id)
                                                v-if="auth.user.id !== user.id"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="text-center" v-if="!users.length">
                                There is active user
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
