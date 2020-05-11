<template>
    <v-toolbar>
        <v-toolbar-title>
            {{ $t('photos.photos')}}
        </v-toolbar-title>

        <v-divider class="mx-4" vertical inset></v-divider>

        <v-spacer></v-spacer>

        <photo-toolbar-search></photo-toolbar-search>

        <v-spacer></v-spacer>

        <photo-modal-create-form></photo-modal-create-form>
    </v-toolbar>
</template>

<script>
  import PhotoToolbarSearch from './PhotoToolbarSearch';
  import PhotoModalCreateForm from './PhotoModalCreateForm';

  export default {
    name: "PhotoToolbar",

    components: {
      PhotoToolbarSearch,
      PhotoModalCreateForm
    }
  }
</script>