<template>
    <FloatingConfigurator/>
    <div
        class="bg-surface-50 dark:bg-surface-950 flex items-center justify-center min-h-screen min-w-[100vw] overflow-hidden">
        <div class="flex flex-col items-center justify-center">
            <div
                style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full bg-surface-0 dark:bg-surface-900 py-20 px-8 sm:px-20" style="border-radius: 53px">
                    <div class="text-center mb-8">
                        <div class="text-surface-900 dark:text-surface-0 text-3xl font-medium mb-4">Welcome
                        </div>
                        <span class="text-muted-color font-medium">Sign in to continue</span>
                    </div>

                    <Form @submit.prevent="handleLogin">
                        <FloatLabel variant="on" class="w-full">
                            <InputText
                                class="text-sm w-full"
                                name="email"
                                id="email"
                                v-model="form.email"
                                type="text"
                                size="large"
                                :class="{ 'p-invalid': page.props.errors.email }"
                            />
                            <label for="email">Email</label>
                        </FloatLabel>
                        <div v-for="errorMessage in page.props.errors.email">
                            <small class="p-error mb-2">{{ errorMessage }}</small>
                        </div>

                        <FloatLabel variant="on" class="w-full mt-8">
                            <div class="w-full" :class="{ 'p-invalid': page.props.errors.password }">
                                <Password
                                    id="password1"
                                    v-model="form.password"
                                    :toggleMask="true"
                                    class="text-sm" fluid
                                    size="large"
                                    :feedback="false">
                                </Password>
                            </div>
                            <label for="password">Password</label>
                        </FloatLabel>
                        <div v-for="errorMessage in page.props.errors.password">
                            <small class="p-error">{{ errorMessage }}</small>
                        </div>

                        <div class="flex items-center justify-between mt-8 mb-8 gap-8">
                            <div class="flex items-center">
                                <Checkbox v-model="form.remember_token" id="rememberToken" binary
                                          class="mr-2"></Checkbox>
                                <label for="rememberToken">Remember me</label>
                            </div>
                            <span class="font-medium no-underline ml-2 text-right cursor-pointer text-primary">Forgot password?</span>
                        </div>
                        <Button type="submit" label="Sign In" class="w-full"></Button>
                    </Form>

                    <p class="text-gray-500 mt-4 text-center">or</p>

                    <Button
                        label="Sign in with Google"
                        severity="secondary"
                        outlined
                        @click="redirectToGoogle"
                        class="w-full"
                    >
                        <template #icon>
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 533.5 544.3" xmlns="http://www.w3.org/2000/svg">
                                <path fill="#4285f4"
                                      d="M533.5 278.4c0-18.3-1.5-36.1-4.3-53.3H272v100.9h146.9c-6.3 33.7-25.1 62.2-53.6 81.3v67.3h86.8c50.8-46.8 81.4-115.7 81.4-196.2z"/>
                                <path fill="#34a853"
                                      d="M272 544.3c72.6 0 133.7-24.1 178.3-65.6l-86.8-67.3c-24.1 16.2-55 25.6-91.5 25.6-70.4 0-130.1-47.6-151.4-111.5H30.9v69.9C75.8 476.6 166.2 544.3 272 544.3z"/>
                                <path fill="#fbbc04"
                                      d="M120.6 325.5c-10.1-29.7-10.1-61.6 0-91.3V164.3H30.9c-30.5 60.6-30.5 131.4 0 192z"/>
                                <path fill="#ea4335"
                                      d="M272 107.1c38.5-.6 75.4 13.8 103.6 39.9l77.4-77.4C404.7 23.6 339.9-1.6 272 0 166.2 0 75.8 67.7 30.9 164.3l89.7 69.9C141.9 154.7 201.6 107.1 272 107.1z"/>
                            </svg>
                        </template>
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import FloatingConfigurator from '../../component/FloatingConfigurator.vue';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import FloatLabel from "primevue/floatlabel";
import Checkbox from 'primevue/checkbox';
import {useForm, usePage} from "@inertiajs/vue3";
import {useToast} from "primevue/usetoast";
import Button from "primevue/button";

const toast = useToast();

import {computed} from 'vue'

const googleRedirectUrl = computed(() => '/cms/auth/google/redirect')
const redirectToGoogle = () => {
    window.location.href = googleRedirectUrl.value
}

defineOptions({
    layout: null
});

const page = usePage();

const form = useForm({
    email: '',
    password: '',
    remember_token: false,
})

const handleLogin = () => {
    form.post('/cms/login', {
        preserveState: true,
        preserveScroll: true,
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Login Failed!',
                life: 3000
            })
        }
    })
}

</script>

<style scoped>
.p-error {
    color: #ef4444 !important;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
}
</style>
