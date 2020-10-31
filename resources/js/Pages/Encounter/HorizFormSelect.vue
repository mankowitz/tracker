<template>
  <div class="inline-block relative w-48">
    <select
      class="block appearance-none w-full bg-white shadow leading-tight focus:outline-none focus:shadow-outline"
      v-model="current_choice"
      @change="$emit('input', current_choice)"
    >
      <option disabled :value="null">Please select</option>
      <option
        v-for="item in options"
        :value="item.id"
        :key="item.id"
        :selected="item.id == value ? 'selected' : ''"
      >
        {{ item.name }}
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
</template>

<script>
export default {  
  props: ["value", "options"],
  data() {
    return {
      current_choice: this.value,
    };
  },
  methods: {
    focus() {
      this.$refs.input.focus();
    },
  },
};
</script>

