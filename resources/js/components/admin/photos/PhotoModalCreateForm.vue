<template>
    <v-dialog v-model="show" max-width="500px" persistent>
        <template v-slot:activator="{on}">
            <v-btn color="success" outlined v-on="on">
                <i class="pe-7s-plus"></i>
                {{ $t('buttons.add')}}
            </v-btn>
        </template>

        <photo-form @hide-modal="hide"></photo-form>
    </v-dialog>
</template>

<script>
  import PhotoForm from './PhotoForm';

  export default {
    name: "PhotoModalCreateForm",

    components: {
      PhotoForm
    },

    data() {
      return {
        show: false
      }
    },

    methods: {
      hide() {
        this.show = false;
      }
    }
  }
</script>