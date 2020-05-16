<template>
    <v-card v-if="articles.length">
        <v-card-title>
            <h2 class="font-weight-black display-3">
                {{ $t('landing.articles') }}
            </h2>
        </v-card-title>
        <v-card-text>
           <article-list :articles="articles" />
        </v-card-text>
    </v-card>

</template>

<script>
  import ArticleList from './ArticleList';

  export default {
    name: "Articles",

    components: {
      ArticleList
    },

    props: {
      'articles': Array
    }
  }
</script>