<template>
    <v-card v-if="photos.length">
        <v-card-title>
            <h2 class="font-weight-black display-3">
                {{ $t('landing.photo_gallery')}}
            </h2>
        </v-card-title>
        <v-card-text>
            <photo-list :photos="photos" />
        </v-card-text>
    </v-card>
</template>

<script>
  import PhotoList from './PhotoList';

  export default {
    name: "Photos",

    components: {
      PhotoList
    },

    props: {
      'photos': Array
    }
  }
</script>