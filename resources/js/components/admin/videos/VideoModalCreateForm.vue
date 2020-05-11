<template>
    <v-dialog v-model="show" max-width="500px" persistent>
        <template v-slot:activator="{on}">
            <v-btn color="success"
                   outlined
                   v-on="on">
                <i class="pe-7s-plus"></i>
                {{ $t('buttons.add')}}
            </v-btn>
        </template>

        <video-form @hide-modal="hide"/>
    </v-dialog>
</template>

<script>
  import VideoForm from './VideoForm';

  export default {
    name: "VideoModalCreateForm",

    components: {
      VideoForm
    },

    data() {
      return {
        show: false
      }
    },

    methods: {
      hide() {
        this.show = false
      }
    }
  }
</script>