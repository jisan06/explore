<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, useForm, usePage, router} from '@inertiajs/vue3';
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {ref} from "vue";
const user= usePage().props.user;
const form = useForm({
    prefixname: user.prefixname,
    firstname: user.firstname,
    middlename: user.middlename,
    lastname: user.lastname,
    suffixname: user.suffixname,
    username: user.username,
    email: user.email,
    password: '',
    photo: '',
    _method: 'patch',
});

const prefixTitles = ['Mr', 'Mrs', 'Ms']

const previewImage = ref(user.avatar ? user.avatar : '');
const selectImage = (event) => {
    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = () => {
            previewImage.value = reader.result;
            form.photo = file;
        };
        reader.readAsDataURL(file);
    }
}
const formSubmit = () => {
    router.post(route('users.update', user.id), form)
}
</script>

<template>
    <Head title="Edit Inventory" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit Inventory</h2>
                    <form @submit.prevent="formSubmit" class="mt-6 space-y-6">
                        <div class="flex gap-5">
                            <div class="3/10">
                                <InputLabel for="prefixname" value="Title" />

                                <select v-model="form.prefixname" :value="form.prefixname" id="prefixname" class="w-full">
                                    <option value="">Select Prefix</option>
                                    <option :value="prefix" v-for="prefix in prefixTitles">{{ prefix }}</option>
                                </select>

                                <InputError class="mt-2" :message="form.errors.prefixname" />
                            </div>
                            <div class="flex-grow">
                                <InputLabel for="firstname" value="First Name" />

                                <input
                                    id="firstname"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.firstname"
                                />

                                <InputError class="mt-2" :message="form.errors.firstname" />
                            </div>
                            <div class="flex-grow">
                                <InputLabel for="middlename" value="Middle Name" />

                                <input
                                    id="middlename"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.middlename"
                                />

                                <InputError class="mt-2" :message="form.errors.middlename" />
                            </div>
                            <div class="flex-grow">
                                <InputLabel for="lastname" value="Last Name" />

                                <input
                                    id="lastname"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.lastname"
                                />

                                <InputError class="mt-2" :message="form.errors.lastname" />
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <div class="flex-grow">
                                <InputLabel for="suffixname" value="Suffix Name" />

                                <input
                                    id="suffixname"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.suffixname"
                                />

                                <InputError class="mt-2" :message="form.errors.suffixname" />
                            </div>
                            <div class="flex-grow">
                                <InputLabel for="username" value="User Name" />

                                <input
                                    id="username"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.username"
                                />

                                <InputError class="mt-2" :message="form.errors.username" />
                            </div>
                            <div class="flex-grow">
                                <InputLabel for="email" value="Email" />

                                <input
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                />

                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <div class="basis-1/3">
                                <InputLabel for="password" value="Password" />

                                <input
                                    id="password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="form.password"
                                />

                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>
                            <div>
                                <InputLabel for="photo" value="Photo" />

                                <input
                                    id="photo"
                                    type="file"
                                    class="mt-1 block w-full"
                                    @change="selectImage($event)"
                                />
                                <img :src=previewImage class="item-image" v-if="previewImage" alt="">

                                <InputError class="mt-2" :message="form.errors.photo" />
                            </div>
                        </div>
                        <div class="flex justify-end gap-4">
                            <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                            </Transition>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
