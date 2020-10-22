<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Create new User
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <form @submit.prevent="submit">
          <label for="name">Name:</label>
          <input id="name" v-model="form.name" />
          <label for="email">Last name:</label>
          <input id="email" v-model="form.email" />
          <label for="unencryptedPassword">Password:</label>
          <input id="unencryptedPassword" v-model="form.unencryptedPassword" />
          <button type="submit">Submit</button>
        </form>
      </div>
    </div>
  </app-layout>
</template>

<script>
export default {
  data() {
    return {
      form: {
        name: null,
        email: null,
        unencryptedPassword: (Math.random()+1).toString(36).substring(2,10),
      },
    };
  },
  methods: {
    submit() {
      this.$inertia.post("/users", this.form);
    },
  },
};
</script>
