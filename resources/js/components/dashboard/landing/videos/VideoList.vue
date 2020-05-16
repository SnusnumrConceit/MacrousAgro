<template>
    <v-slide-group >
        <v-slide-item v-for="video in videos" :key="video.id">
            <v-card
                    class="mx-3"
                    max-width="400"
            >
                <v-card-text>
                    <video :src="video.src" controls height="240" width="320"></video>
                </v-card-text>
                <v-card-title class="text-center">
                    {{ video.title }}
                </v-card-title>
            </v-card>
        </v-slide-item>
    </v-slide-group>
</template>

<script>
  export default {
    name: "VideoList",

    props: {
      'videos': Array
    }
  }
</script>