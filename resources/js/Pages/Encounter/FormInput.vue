<template>
  <div class="mb-6">
    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
      {{ display }}
      <input
        class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
        :class="{'border-red-500':$page.errors[name]}"
        :value="value"
        @input="$emit('input', $event.target.value)"
        ref="input"
      />
    </label>
    <p v-if="$page.errors[name]" class="text-red-500 text-xs italic">
      {{ $page.errors[name][0] }}
    </p>
  </div>
</template>

<script>
export default {
  props: ["value", "name", "display"],

  methods: {
    focus() {
      this.$refs.input.focus();
    },
  },
};
</script>

