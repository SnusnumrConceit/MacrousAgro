<template>
    <v-text-field v-model="search.keyword"
                  @keyup.enter="onSearch"
                  append-icon="search"
                  :label="$t('placeholders.search')"
                  single-line>
    </v-text-field>
</template>

<script>
  export default {
    name: "CategoryToolbarSearch",

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
          this.$root.$emit('category-searching', { search: this.search, page: 1 });
        }

        if (! after.length && before.length > 0) {
          this.$root.$emit('category-search-reset', { search: this.search, page: 1 });
        }
      }
    },
  }
</script>