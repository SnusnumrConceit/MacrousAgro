<template>
    <v-toolbar>
        <v-toolbar-title>
            {{ $t('categories.categories')}}
        </v-toolbar-title>

        <v-divider class="mx-4" vertical inset></v-divider>
        <v-spacer></v-spacer>

       <category-toolbar-search></category-toolbar-search>

        <v-spacer></v-spacer>

        <category-modal-create-form></category-modal-create-form>
    </v-toolbar>
</template>

<script>
  import CategoryModalCreateForm from './CategoryModalCreateForm';
  import CategoryToolbarSearch from './CategoryToolbarSearch';

  export default {
    name: "CategoryToolbar",

    components: {
      CategoryModalCreateForm,
      CategoryToolbarSearch
    }
  }
</script>