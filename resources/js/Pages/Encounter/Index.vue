<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Active Patients
      </h2>
    </template>
    <edit-modal />
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
          <table class="table-fixed w-full">
            <thead>
              <tr class="bg-gray-100">
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Location</th>
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
                <td class="border px-4 py-2">{{ encounter.location }}</td>
                <td>
                  <select
                    v-model="encounter.provider_id"
                    @change="update('provider_id',encounter)"
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
                </td>
                <td class="border px-4 py-2">
                  <textarea
                    @keydown.enter="update('chief_complaint',encounter)"
                    @blur="update('chief_complaint',encounter)"
                    v-model="encounter.chief_complaint"
                  ></textarea                  >
                </td>
                <td class="border px-4 py-2">
                  <textarea
                    @keydown.enter="update('comments',encounter)"
                    @blur="update('comments',encounter)"
                    v-model="encounter.comments"
                  ></textarea
                  ><br />
                </td>
                <td class="border px-4 py-2">
                  <button
                    @click="encounter.is_complete=1; update('is_complete',encounter)"
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

export default {
  props: ["data", "errors"],
  components: {
    AppLayout,
  },
  computed: {
    encounters() {
      return this.$page.encounters.filter(i=>i.is_complete==0);
    }
  },
  methods: {
    edit(id) {
//      window.open(route("encounter.edit", id));
//      this.$inertia.visit(this.route('encounter.create', {contact: contact.id}), {only: ['contact']})
    },
    create() {
      this.$inertia.visit(this.route('encounter.create'));
    },
    update(field, encounter) {
      let payload = {
        field_name: field,
        new_value: encounter[field],
        _method: "PUT",
      };
      this.$inertia.post("/encounter/" + encounter.id, payload);
    },
  },
};
</script>