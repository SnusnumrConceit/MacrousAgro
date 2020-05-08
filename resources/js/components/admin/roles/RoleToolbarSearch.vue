<template>
    <v-text-field v-model="search.keyword" append-icon="search"
                  :label="$t('placeholders.search')"
                  single-line>
    </v-text-field>
</template>

<script>
  export default {
    name: "RoleToolbarSearch",

    data() {
      return {
        search: {
          keyword: ''
        }
      }
    },

    watch: {
      'search.keyword': function (after, before) {
        if (after.length > 3) {
          this.$root.$emit('role-searching', this.search);
        }

        if (! after.length) {
          this.$root.$emit('role-search-reset', this.search);
        }
      }
    }
  }
</script>

<style scoped>

</style>