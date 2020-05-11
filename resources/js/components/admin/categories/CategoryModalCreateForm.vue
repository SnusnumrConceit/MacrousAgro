<template>
    <v-dialog v-model="show" max-width="500px"> <!-- @click:outside="resetForm" -->
        <template v-slot:activator="{on}">
            <v-btn outlined color="success" class="" dark v-on="on">
                <i class="pe-7s-plus"></i> {{ $t('buttons.add')}}
            </v-btn>
        </template>

        <category-form @hide-modal="hide"></category-form>

    </v-dialog>
</template>

<script>
  import CategoryForm from './CategoryForm';

  export default {
    name: "CategoryModalCreateForm",

    components: {
      CategoryForm
    },

    data() {
      return {
        show: false,
      }
    },

    methods: {
      hide() {
        this.show = false;
      }
    }
  }
</script>