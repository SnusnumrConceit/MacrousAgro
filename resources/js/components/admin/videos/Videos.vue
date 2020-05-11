<template>
    <v-card>
        <video-toolbar></video-toolbar>

        <v-card-text>
            <video-list />
        </v-card-text>
    </v-card>
</template>

<script>
  import VideoList from './VideoList';
  import VideoToolbar from './VideoToolbar';

  export default {
    name: "index",

    components: {
      VideoToolbar,
      VideoList
    }
  }
</script>
