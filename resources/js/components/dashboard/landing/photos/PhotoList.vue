<template>
    <v-slide-group >
        <v-slide-item v-for="photo in photos" :key="photo.id">
            <v-card
                    class="mx-3"
                    max-width="600"
            >
                <v-img :src="photo.path || 'https://cdn.vuetifyjs.com/images/cards/docks.jpg'"
                       alt=""
                       width="680px"
                       height="480px"
                       class="white--text align-end">
                    <v-card-title class="text-center">
                        {{ photo.title }}
                    </v-card-title>
                </v-img>
            </v-card>
        </v-slide-item>
    </v-slide-group>
</template>

<script>
  export default {
    name: "PhotoList",

    props: {
      'photos': Array
    }
  }
</script>