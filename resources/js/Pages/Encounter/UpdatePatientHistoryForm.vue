<template>
</template>

<script>
import JetButton from "./../../Jetstream/Button";
import JetFormSection from "./../../Jetstream/FormSection";
import JetInput from "./../../Jetstream/Input";
import JetInputError from "./../../Jetstream/InputError";
import JetLabel from "./../../Jetstream/Label";
import JetActionMessage from "./../../Jetstream/ActionMessage";
import JetSecondaryButton from "./../../Jetstream/SecondaryButton";

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

  props: ["patient"],

  data() {
    return {
      form: this.$inertia.form(
        {
          _method: "PUT",
          histories: {
            pmh: this.patient.histories.pmh,
            psh: this.patient.histories.psh,
            meds: this.patient.histories.meds,
            all: this.patient.histories.all,
            fh: this.patient.histories.fh,
            sh: this.patient.histories.sh,
          },
        },
        {
          bag: "updatePatientHistory",
          resetOnSuccess: false,
        }
      ),
    };
  },

  methods: {
    updatePatientHistory() {
      this.form.post(route("patient-histories.update"), {
        preserveScroll: true,
      });
    },
  },
};
</script>
