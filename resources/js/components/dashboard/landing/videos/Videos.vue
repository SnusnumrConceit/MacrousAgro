<template>
    <v-card v-if="videos.length">
        <v-card-title>
            <h2 class="font-weight-black display-3">
                {{ $t('landing.video_gallery')}}
            </h2>
        </v-card-title>
        <v-card-text>
            <video-list :videos="videos" />
        </v-card-text>
    </v-card>
</template>

<script>
  import VideoList from './VideoList';

  export default {
    name: "Videos",

    components: {
      VideoList
    },

    props: {
      'videos': Array
    }
  }
</script>