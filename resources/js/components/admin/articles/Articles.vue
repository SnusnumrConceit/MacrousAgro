<template>
    <div>
        <v-card>
            <article-toolbar />

            <v-card-text>
                <article-list />
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
  import ArticleToolbar from './ArticleToolbar';
  import ArticleList from './ArticleList';

  export default {
    name: "index",

    components: {
      ArticleToolbar,
      ArticleList
    }
  }
</script>
