<template>
    <v-toolbar>
        <v-toolbar-title>
            {{ $t('videos.videos')}}
        </v-toolbar-title>

        <v-divider class="mx-4" vertical inset></v-divider>

        <v-spacer />

        <video-toolbar-search />

        <v-spacer />

        <video-modal-create-form />
    </v-toolbar>
</template>

<script>
  import VideoToolbarSearch from './VideoToolbarSearch';
  import VideoModalCreateForm from './VideoModalCreateForm';

  export default {
    name: "VideoToolbar",

    components: {
      VideoToolbarSearch,
      VideoModalCreateForm
    }
  }
</script>