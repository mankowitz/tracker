<template>
    <transition name="fade-shrink-in" appear>
      <div
        v-show="encounter || create"
        @click="closeModal()"
        class="absolute top-0 left-0 h-full w-full"
        style="background-color: hsla(0, 0%, 0%, 0.5); z-index: 99998"
      >
        <form
          class="max-w-sm w-full mx-auto bg-white rounded p-4 mt-8 z-10 shadow-lg"
          @click.stop
          @submit.prevent="submit"
        >
          <h2>Demographics</h2>

          <input type="hidden" name="encounter_id" :value="form.encounter_id" />
          <input type="hidden" name="patient_id" :value="form.patient_id" />
          <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
              <autocomplete
                searchUrl="/patient/search"
                v-slot="opt"
                display="Last Name"
                name="lastname"
                v-model="form.lastname"
                @found="populate"
              >
                <span>
                  <b>{{ opt.patient.lastname }},</b>
                  {{ opt.patient.firstname }}; ({{ opt.patient.dob }}
                  {{ opt.patient.sex }})
                </span>
              </autocomplete>
            </div>
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
              <form-input
                name="first_name"
                display="First Name"
                v-model="form.firstname"
              />
            </div>
          </div>

          <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
              <form-input
                name="dob"
                display="Date of Birth"
                v-model="form.dob"
              />
            </div>

            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
              <form-input name="mrn" display="MRN" v-model="form.mrn" />
            </div>

            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
              <form-select
                name="sex"
                display="Sex"
                v-model="form.sex"
                :options="[
                  { name: 'Male', id: 'M' },
                  { name: 'Female', id: 'F' },
                  { name: 'Other', id: 'O' },
                ]"
              />
            </div>
          </div>

          <h2
            v-show="showHistories"
            @click="showHistories = false"
            class="cursor-pointer"
          >
            Hide Histories
          </h2>
          <h2
            v-show="!showHistories"
            @click="showHistories = true"
            class="cursor-pointer"
          >
            Show Histories
          </h2>
          <div v-show="showHistories">
            <form-input
              name="pmh"
              display="Past Medical History"
              v-model="form.pmh"
            />
            <form-input
              name="psh"
              display="Past Surgical History"
              v-model="form.psh"
            />
            <form-input name="meds" display="Medications" v-model="form.meds" />
            <form-input name="all" display="Allergies" v-model="form.all" />
          </div>

          <h2>This visit</h2>
          <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
              <form-input
                name="location"
                display="Location"
                v-model="form.location"
              />
            </div>
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
              <form-input
                name="chief_complaint"
                display="Chief Complaint"
                v-model="form.chief_complaint"
              />
            </div>
          </div>
          <form-select
            name="provider_id"
            display="Assigned To"
            v-model="form.provider_id"
            :options="users"
          />
          <form-input
            name="comments"
            display="Comments"
            v-model="form.comments"
          />

          <div class="md:flex md:items-center">
            <div class="md:w-1/3"></div>
            <div class="md:w-2/3">
              <button
                @click="submit"
                class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                type="button"
              >
                Submit
              </button>
            </div>
          </div>
        </form>
      </div>
    </transition>
</template>

<script>
export default {
  props: {
    contact: Object,
  },
  data() {
    return {
      form: {
        first_name: null,
      },
    };
  },
};
</script>
<script>
import AppLayout from "./../../Layouts/AppLayout";
import FormInput from "./FormInput";
import Autocomplete from "./Autocomplete";
import FormSelect from "./FormSelect";

export default {
  props: ["users", "encounter", "errors", "create"],
  data() {
    return {
      showHistories: false,
      form: {
        firstname: this.encounter?.patient?.firstname,
        lastname: this.encounter?.patient?.lastname,
        dob: this.encounter?.patient?.histories?.dob,
        mrn: this.encounter?.patient?.histories?.mrn,
        sex: this.encounter?.patient?.histories?.sex,
        pmh: this.encounter?.patient?.histories?.pmh,
        psh: this.encounter?.patient?.histories?.psh,
        meds: this.encounter?.patient?.histories?.meds,
        all: this.encounter?.patient?.histories?.all,
        fh: this.encounter?.patient?.histories?.fh,
        sh: this.encounter?.patient?.histories?.sh,
        location: this.encounter?.location,
        comments: this.encounter?.comments,
        chief_complaint: this.encounter?.chief_complaint,
        provider_id: this.encounter?.provider_id,
        encounter_id: this.encounter?.id,
        patient_id: this.encounter?.patient?.id,
      },
    };
  },
  mounted() {
    if (this.encounter) {
      // populate form...
    }
  },
  components: {
    AppLayout,
    FormInput,
    FormSelect,
    Autocomplete,
  },
  methods: {
    closeModal() {
      this.$inertia.visit(this.route("encounter"));
    },
    submit() {
      this.$inertia.post("/encounter", this.form);
      this.create = false;
    },
    populate(e) {
      this.form.lastname = e.lastname;
      this.form.firstname = e.firstname;
      this.form.dob = e.dob;
      this.form.mrn = e.mrn;
      this.form.sex = e.sex;
      this.form.patient_id = e.id;
    },
  },
};
</script>