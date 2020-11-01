<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <select v-model="is_complete" @change="refetch">
          <option value="0">Active</option>
          <option value="1">Discharged</option>
        </select>
        Patients sorted by:
        <select v-model="sortBy">
          <option value="arr">Arrival</option>
          <option value="name">Name</option>
          <option value="dob">DOB</option>
          <option value="fac">Facility</option>
          <option value="loc">Location</option>
          <option value="spot">Spot</option>
          <option value="prov">Provider</option>
          <option value="rn">Nurse</option>
          <option value="cm">Case Mgr</option>
        </select>
        <span v-if="is_complete==1">
          between
          <input v-model="startDate">
          and
          <input v-model="endDate">
        </span>
        <button
          @click="$inertia.visit('/print-dashboard')"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 text-xs rounded my-3 float-right"
        >
          Print Page
        </button>
      </h2>
    </template>
    <div class="mx-auto text-center">
      Totals:
      <span v-for="(value, name) in totals" :key="name">
        <b>{{ name }}</b
        >: {{ value }};&nbsp;&nbsp;&nbsp;
      </span>
    </div>
    <edit-modal />
    <div class="py-12">
      <div class="max-w-full mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
          <div
            class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
            role="alert"
            v-if="$page.flash.message"
          >
            <div class="flex">
              <div>
                <p class="text-sm">{{ $page.flash.message }}</p>
              </div>
            </div>
          </div>
          <button v-if="is_complete==0"
            @click="create()"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3"
          >
            Create New Encounter
          </button>
          <table class="table-auto w-full">
            <thead>
              <tr class="bg-gray-100">
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Facility<br />Location<br />Spot</th>
                <th class="px-4 py-2">Provider<br />Nurse<br />Case Mgr</th>
                <th class="px-4 py-2">Chief Complaint</th>
                <th class="px-4 py-2">Comments</th>
                <th class="px-4 py-2 print:hidden">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="encounter in encounters" :key="encounter.id">
                <td
                  class="border px-4 py-2 cursor-pointer"
                  @click="edit(encounter.id)"
                >
                  {{ encounter.patient.lastname }},
                  {{ encounter.patient.firstname }}
                  ({{ ageSex(encounter.patient) }}) <br />DOB:
                  {{ [encounter.patient.dob, "YYYY-MM-DD"] | moment("M/D/Y") }}
                </td>
                <td class="border px-4 py-2">
                  <div class="inline-block relative w-14">Fac:</div>
                  <horiz-form-select
                    v-model="encounter.facility"
                    @input="update('facility', encounter)"
                    :options="facilities"
                  />
                  <br />

                  <div class="inline-block relative w-14">Loc:</div>

                  <horiz-form-select
                    v-model="encounter.location"
                    @input="update('location', encounter)"
                    :options="locations"
                  />

                  <br />
                  <div class="inline-block relative w-14">Spot:</div>
                  <input
                    class="inline-block relative w-48"
                    v-model="encounter.spot"
                    @input="update('spot', encounter)"
                  />
                </td>
                <td>
                  <div class="inline-block relative w-14">Prov:</div>
                  <horiz-form-select
                    v-model="encounter.provider_id"
                    @input="update('provider_id', encounter)"
                    :options="providers"
                  />
                  <br />

                  <div class="inline-block relative w-14">RN:</div>
                  <horiz-form-select
                    v-model="encounter.nurse_id"
                    @input="update('nurse_id', encounter)"
                    :options="nurses"
                  />
                  <br />

                  <div class="inline-block relative w-14">CM:</div>
                  <horiz-form-select
                    v-model="encounter.case_manager_id"
                    @input="update('case_manager_id', encounter)"
                    :options="caseMgrs"
                  />
                </td>
                <td class="border px-4 py-2">
                  <textarea
                    class="rounded-lg resize-none w-full h-full"
                    @keydown.enter="update('chief_complaint', encounter)"
                    @blur="update('chief_complaint', encounter)"
                    v-model="encounter.chief_complaint"
                  ></textarea>
                </td>
                <td class="border px-4 py-2">
                  <textarea
                    @keydown.enter="update('comments', encounter)"
                    @blur="update('comments', encounter)"
                    v-model="encounter.comments"
                  ></textarea
                  ><br />
                </td>
                <td class="border px-4 py-2 print:hidden">
                  <button
                    @click="
                      encounter.is_complete = 1;
                      update('is_complete', encounter);
                    "
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                  >
                    Discharge
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </app-layout>
</template>
<script>
import AppLayout from "./../../Layouts/AppLayout";
import EditModal from "./EditModal";
import HorizFormSelect from "./HorizFormSelect";

export default {
  props: ["data", "errors"],
  data() {
    return {
      sortBy: "arr",
      is_complete: this.$page.params?.is_complete || 0,
      startDate: this.$moment().add(-2,"w").format("M/D/Y"),
      endDate: this.$moment().add(1,"d").format("M/D/Y"),
      facilities: [
        { name: "Alexandria", id: "Alexandria" },
        { name: "NOLA", id: "NOLA" },
      ],
      locations: [
        { name: "Medical - Caregiver", id: "Medical - Caregiver" },
        { name: "Medical", id: "Medical" },
        { name: "General Population", id: "General Population" },
      ],
    };
  },
  components: {
    AppLayout,
    EditModal,
    HorizFormSelect,
  },
  computed: {
    encounters() {
      return this.$page.encounters
        .filter((i) => i.is_complete == this.is_complete)
        .sort(this.sortFunction);
    },
    providers() {
      return this.$page.users.filter((i) => i.user_type.short == "prov");
    },
    caseMgrs() {
      return this.$page.users.filter((i) => i.user_type.short == "cm");
    },
    nurses() {
      return this.$page.users.filter((i) => i.user_type.short == "rn");
    },
    totals() {
      let tally = {};
      this.$page.encounters.forEach((e) => {
        if (!tally[e.location]) tally[e.location] = 0;
        tally[e.location]++;
      });
      console.log(tally);
      return tally;
    },
  },
  mounted() {
    setInterval(
      () =>
        this.$inertia.reload({ only: ["encounters"], preserveScroll: true }),
      45000
    );
  },
  methods: {
    refetch() {
      this.$inertia.visit(this.route("encounter.index"), {
        preserveScroll: true,
        data: {
          is_complete: this.is_complete,
          startDate: this.startDate,
          endDate: this.endDate,
        },
      });
    },
    sortFunction(a, b) {
      switch (this.sortBy) {
        case "arr":
          return a.created_at > b.created_at ? 1 : -1;
        case "name":
          return a.patient.lastname > b.patient.lastname ? 1 : -1;
        case "dob":
          return a.patient.dob > b.patient.dob ? 1 : -1;
        case "fac":
          return this.stringComp(a.facility, b.facility);
        case "loc":
          return this.stringComp(a.location, b.location);
        case "spot":
          return this.stringComp(a.spot, b.spot);
        case "prov":
          return this.stringComp(a.provider.name, b.provider.name);
        case "rn":
          return this.stringComp(a.nurse.name, b.nurse.name);
        case "cm":
          return this.stringComp(a.caseManager.name, b.caseManager.name);
      }
    },
    stringComp(a, b) {
      if (!a) return -1;
      if (!b) return 1;
      if (a == b) return 0;
      return a > b ? 1 : -1;
    },
    ageSex(pt) {
      return this.$moment().diff(pt.dob, "years", false) + "/" + pt.sex;
    },

    edit(id) {
      this.$inertia.visit(this.route("encounter.edit", { encounter: id }));
    },
    create() {
      this.$inertia.visit(this.route("encounter.create"));
    },
    update(field, encounter) {
      let payload = {
        field_name: field,
        new_value: encounter[field],
        queryString: window.location.search.replace(/^\?/,""),
        _method: "PUT",
      };
      this.$inertia.post(this.route("encounter.update", {encounter: encounter.id}), payload, {
        preserveScroll: true,
      });
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
  svg {
    display: none;
  }
}
</style>