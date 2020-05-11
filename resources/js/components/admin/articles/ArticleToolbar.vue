<template>
    <v-toolbar>
        <v-toolbar-title>
            {{ $t('articles.articles') }}
        </v-toolbar-title>

        <v-divider class="mx-4" vertical inset></v-divider>

        <v-spacer />

        <article-toolbar-search />

        <v-spacer />

        <article-modal-create-form />

    </v-toolbar>
</template>

<script>
  import ArticleToolbarSearch from './ArticleToolbarSearch';
  import ArticleModalCreateForm from './ArticleModalCreateForm';

  export default {
    name: "ArticleToolbar",

    components: {
      ArticleToolbarSearch,
      ArticleModalCreateForm
    }
  }
</script>