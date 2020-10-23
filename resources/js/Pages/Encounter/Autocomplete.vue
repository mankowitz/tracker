<template>
  <div class="mb-6">
    <p class="text-red-500 text-xs italic" v-show="searchResults.length >= 50">
      <b>Note!</b> More than 50 results found; try using more specific terms
    </p>
    <label
      class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
    >
      {{ display }}
      <input
        class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
        :class="{ 'border-red-500': $page.errors[name] }"
        v-model="searchText"
        @input="$emit('input', $event.target.value)"
        @keyup="autoComplete"
        placeholder="Enter first few letters"
        ref="input"
      />
    </label>
    <p v-if="$page.errors[name]" class="text-red-500 text-xs italic">
      {{ $page.errors[name][0] }}
    </p>

    <div class="" v-show="searchResults.length > 1">
      <ul class="">
        <li
          class=""
          v-for="opt in searchResults"
          :value="opt.id"
          :key="opt.id"
          @click="select(opt)"
        >
          <slot :patient="opt">
            {{ opt }}
          </slot>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    searchUrl: {
      type: String,
      default: "/patient/search",
    },
    debounceTime: {
      type: Number,
      default: 300,
    },
    display: {
      type: String,
      default: "display",
    },
    name: {
      type: String,
      default: "Name",
    },
    value: {
      type: String,
      default: "",
    },
  },
  methods: {
    autoComplete: function () {
      if (this.timeout) clearTimeout(this.timeout);
      this.timeout = setTimeout(() => {
          console.log("searching...", this.searchText);
        if (this.searchText.length >= 2) {
          console.log("searching...", this.searchText);
          axios
            .get(this.searchUrl, {
              params: { searchText: this.searchText },
            })
            .then((response) => {
              console.log(response);
              this.searchResults = response.data;
            });
        }
      }, this.debounceTime);
    },

    select(opt) {
      this.searchResults = [];
      this.searchText = opt[this.name];
      this.$emit("input", opt[this.name]);
      this.$emit("found", opt);
    },
  },
  mounted() {
    this.searchText = this.value;
  },
  data() {
    return { searchText: this.value, searchResults: [], timeout: null };
  },
};
</script>
