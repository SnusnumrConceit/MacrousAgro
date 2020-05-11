<template>
    <v-dialog v-model="show" max-width="1000px" persistent>
        <template v-slot:activator="{on}">
            <v-btn color="success" outlined v-on="on">
                <i class="pe-7s-plus"></i> {{ $t('buttons.add') }}
            </v-btn>
        </template>

        <article-form @hide-modal="hide"/>

    </v-dialog>
</template>

<script>
  import ArticleForm from './ArticleForm';

  export default {
    name: "ArticleModalCreateForm",

    components: {
      ArticleForm
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