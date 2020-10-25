<template>
  <span>
    <span @click="tableVisible = true" v-if="!tableVisible">{{
      newValue || "None"
    }}</span>

    <jet-dialog-modal :show="tableVisible" @close="tableVisible = false" max-width="2xl">
      <template #title> Select Spot </template>

      <template #content>
        <table>
          <tr v-for="row in 10" :key="row">
            <td
              :class="{ selected: newValue == toString(row, col) }"
              v-for="col in 15"
              :key="col"
              @click="accept(row, col)"
            >
              {{ toString(row, col) }}
            </td>
          </tr>
        </table>
      </template>

      <template #footer>
        <jet-secondary-button @click.native="tableVisible = false">
          Cancel
        </jet-secondary-button>
      </template>
    </jet-dialog-modal>
  </span>
</template>

<script>
import JetDialogModal from "./../../Jetstream/DialogModal";
import JetSecondaryButton from "./../../Jetstream/SecondaryButton";

export default {
  components: {
    JetDialogModal,
    JetSecondaryButton,
  },

  props: ["value"],
  mounted() {
    this.newValue = this.value;
  },
  data() {
    return {
      tableVisible: false,
      newValue: null,
    };
  },
  methods: {
    accept(row, col) {
      this.newValue = this.toString(row, col);
      this.$emit("input", this.newValue);
      this.tableVisible = false;
    },
    toString(row, col) {
      return row + "-" + col;
    },
    showTable() {
      this.tableVisible = true;
    },
  },
};
</script>

<style scoped>
.selected {
  background-color: lightgreen;
}
td {
  background: white;
  border: 1px black solid;
  padding: 2px;
  text-align: center;
  vertical-align: middle;
}
</style>

