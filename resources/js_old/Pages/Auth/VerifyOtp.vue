<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { objectToString } from '@vue/shared';

const props = defineProps({
    // user: Object,
});

const form = useForm({
    // email: props.user.email,
    otp:''
});

const submit = () => {
    form.post(route('login'), {
        onFinish: (data) => {
            console.log("we're back",form.errors);
        }//form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Please Verify the OTP" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="otp" value="OTP" />
                <TextInput
                    id="otp"
                    type="number"
                    class="mt-1 block w-full"
                    v-model="form.otp"
                    required
                    autofocus
                    autocomplete="otp"
                />

                <InputError class="mt-2" :message="form.errors.otp" />
            </div>
            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Next
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
