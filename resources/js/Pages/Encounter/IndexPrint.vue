<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-md text-gray-800 leading-tight">
        Active Patients
      </h2>
    </template>
    <div class="py-12">
      <div class="max-w-full mx-auto">
        <div class="bg-white px-2 py-4"></div>
        <table class="table-auto w-full">
          <thead>
            <tr class="bg-gray-100">
              <th class="px-2 py-2">Name</th>
              <th class="px-2 py-2">Facility<br />Location<br />Spot</th>
              <th class="px-2 py-2">Provider</th>
              <th class="px-2 py-2">Chief Complaint</th>
              <th class="px-2 py-2">Comments</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="encounter in encounters"
              :key="encounter.id"
              class="bg-white text-sm"
            >
              <td class="border px-2 py-2 cursor-pointer">
                {{ encounter.patient.lastname }},
                {{ encounter.patient.firstname }}
                ({{ ageSex(encounter.patient) }}) <br />DOB:
                {{ [encounter.patient.dob, "YYYY-MM-DD"] | moment("M/D/Y") }}
              </td>
              <td class="border px-6 py-2">
                <div class="inline-block relative w-15">Fac:</div>
                {{ encounter.facility }}
                <br />
                <div class="inline-block relative w-15">Loc:</div>
                {{ encounter.location }}
                <br />
                <div class="inline-block relative w-15">Spot:</div>
                {{ encounter.spot }}
                <br />
              </td>
              <td class="border px-2 py-2">
                <div class="inline-block relative w-15">Prov:</div>
                {{ providerName(encounter.provider) }}
                <div class="inline-block relative w-15">RN:</div>
                {{ providerName(encounter.nurse) }}
                <div class="inline-block relative w-15">CM:</div>
                {{ providerName(encounter.case_manager) }}
              </td>
              <td class="border px-2 py-2">{{ encounter.chief_complaint }}</td>
              <td class="border px-2 py-2">{{ encounter.comments }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </app-layout>
</template>
<script>
import AppLayout from "./../../Layouts/AppLayout";

export default {
  mounted() {
    window.print();
  },
  props: ["data", "errors"],

  methods: {
    ageSex(pt) {
      return this.$moment().diff(pt.dob, "years", false) + "/" + pt.sex;
    },
    providerName(provider) {
      return provider?.name || "Unassigned";
    },
  },
  components: {
    AppLayout,
  },
  computed: {
    encounters() {
      return this.$page.encounters.filter((i) => i.is_complete == 0);
    },
  },
};
</script>

<style scoped>
@media print {
  body {
    height: auto;
    font-size: 10pt; /* changing to 10pt has no impact */
  }
}
</style>