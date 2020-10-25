<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Active Patients
      </h2>
    </template>
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
          <button
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
                <th class="px-4 py-2">Provider</th>
                <th class="px-4 py-2">Chief Complaint</th>
                <th class="px-4 py-2">Comments</th>
                <th class="px-4 py-2">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="encounter in encounters" :key="encounter.id">
                <td class="border px-4 py-2">
                  {{ encounter.patient.lastname }},
                  {{ encounter.patient.firstname }}<br />DOB:
                  {{ encounter.patient.dob }}; Sex: {{ encounter.patient.sex }}
                </td>
                <td class="border px-6 py-2">
                  <div class="inline-block relative w-20">Facility:</div>
                  <div class="inline-block relative w-48">
                    <select
                      class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                      v-model="encounter.facility"
                      @change="update('facility', encounter)"
                    >
                      <option disabled :value="null">
                        Please select Facility
                      </option>
                      <option
                        v-for="facility in facilities"
                        :value="facility.id"
                        :key="facility.id"
                        :selected="
                          encounter.facility == facility.id ? 'selected' : ''
                        "
                      >
                        {{ facility.name }}
                      </option>
                    </select>
                    <div
                      class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"
                    >
                      <svg
                        class="fill-current h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                      >
                        <path
                          d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                        />
                      </svg>
                    </div>
                  </div>
                  <br />

                  <div class="inline-block relative w-20">Location:</div>
                  <div class="inline-block relative w-48">
                    <select
                      class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                      v-model="encounter.location"
                      @change="update('location', encounter)"
                    >
                      <option disabled :value="null">
                        Please select Location
                      </option>
                      <option
                        v-for="location in locations"
                        :value="location.id"
                        :key="location.id"
                        :selected="
                          encounter.location == location.id ? 'selected' : ''
                        "
                      >
                        {{ location.name }}
                      </option>
                    </select>
                    <div
                      class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"
                    >
                      <svg
                        class="fill-current h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                      >
                        <path
                          d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                        />
                      </svg>
                    </div>
                  </div>

                  <br />
                  <div class="inline-block relative w-20">Spot:</div>
                  <select-grid
                    v-model="encounter.spot"
                    @input="update('spot', encounter)"
                  />
                </td>
                <td>
                  <div class="inline-block relative w-48">
                    <select
                      class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                      v-model="encounter.provider_id"
                      @change="update('provider_id', encounter)"
                    >
                      <option
                        v-for="user in $page.users"
                        :value="user.id"
                        :key="user.id"
                        :selected="
                          user.id == encounter.provider_id ? 'selected' : ''
                        "
                      >
                        {{ user.name }}
                      </option>
                    </select>
                    <div
                      class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"
                    >
                      <svg
                        class="fill-current h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                      >
                        <path
                          d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                        />
                      </svg>
                    </div>
                  </div>
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
                <td class="border px-4 py-2">
                  <button
                    @click="
                      encounter.is_complete = 1;
                      update('is_complete', encounter);
                    "
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                  >
                    Mark Complete
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
import SelectGrid from "./SelectGrid";

export default {
  props: ["data", "errors"],
  data() {
    return {
      facilities: [
        { name: "Alexandria", id: "Alexandria" },
        { name: "NOLA", id: "NOLA" },
      ],
      locations: [
        { name: "Medical", id: "Medical" },
        { name: "General Population", id: "General Population" },
      ],
    };
  },
  components: {
    AppLayout,
    EditModal,
    SelectGrid,
  },
  computed: {
    encounters() {
      return this.$page.encounters.filter((i) => i.is_complete == 0);
    },
  },
  mounted() {
    setInterval(
      () =>
        this.$inertia.reload({ only:["encounters"], preserveScroll: true }),
      10000
    );
  },
  methods: {
    edit(id) {
      //      window.open(route("encounter.edit", id));
      //      this.$inertia.visit(this.route('encounter.create', {contact: contact.id}), {only: ['contact']})
    },
    create() {
      this.$inertia.visit(this.route("encounter.create"));
    },
    update(field, encounter) {
      let payload = {
        field_name: field,
        new_value: encounter[field],
        _method: "PUT",
      };
      this.$inertia.post("/encounter/" + encounter.id, payload, {
        preserveScroll: true,
      });
    },
  },
};
</script>