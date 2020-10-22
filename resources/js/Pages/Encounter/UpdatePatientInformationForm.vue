<template>
    <jet-form-section @submitted="updatePatientInformation">
        <template #title>
            Patient Information
        </template>

        <template #description>
            Update longitudinal and demographic information about this patient.
        </template>

        <template #form>
            <!-- Last Name -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="lastname" value="Last Name" />
                <jet-input id="lastname" type="text" class="mt-1 block w-full" v-model="form.lastname" />
                <jet-input-error :message="form.error('lastname')" class="mt-2" />
            </div>

            <!-- first Name -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="firstname" value="first Name" />
                <jet-input id="firstname" type="text" class="mt-1 block w-full" v-model="form.firstname" />
                <jet-input-error :message="form.error('firstname')" class="mt-2" />
            </div>

            <!-- DOB -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="dob" value="Date of Birth" />
                <jet-input id="dob" type="text" class="mt-1 block w-full" v-model="form.dob" />
                <jet-input-error :message="form.error('dob')" class="mt-2" />
            </div>

            <!-- MRN -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="mrn" value="Date of Birth" />
                <jet-input id="mrn" type="text" class="mt-1 block w-full" v-model="form.mrn" />
                <jet-input-error :message="form.error('mrn')" class="mt-2" />
            </div>

        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import JetButton from './../../Jetstream/Button'
    import JetFormSection from './../../Jetstream/FormSection'
    import JetInput from './../../Jetstream/Input'
    import JetInputError from './../../Jetstream/InputError'
    import JetLabel from './../../Jetstream/Label'
    import JetActionMessage from './../../Jetstream/ActionMessage'
    import JetSecondaryButton from './../../Jetstream/SecondaryButton'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
        },

        props: ['patient'],

        data() {
            return {
                form: this.$inertia.form({
                    '_method': 'PUT',
                    lastname: this.patient.lastname,
                    firstname: this.patient.firstname,
                    dob: this.patient.dob,
                    mrn: this.patient.mrn,
                }, {
                    bag: 'updatePatientInformation',
                    resetOnSuccess: false,
                }),
            }
        },

        methods: {
            updatePatientInformation() {

                this.form.post(route('patient-information.update'), {
                    preserveScroll: true
                });
            },

        },
    }
</script>
