<script setup>
import {Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
const {user} = usePage().props;
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
                        <div>
                            <h3>User Details</h3>
                            <table class="user-table text-center w-full">
                                <tr>
                                    <th>Name</th>
                                    <td>{{user.fullname}}</td>
                                    <th>Email</th>
                                    <td>{{user.email}}</td>
                                </tr>
                                <tr>
                                    <th>Username</th>
                                    <td>{{user.firstname}}</td>
                                    <th>Photo</th>
                                    <td>
                                        <img class="max-w-[100px]" :src="user.avatar" v-if="user.avatar" alt="">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
