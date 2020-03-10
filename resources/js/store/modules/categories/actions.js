import axios from 'axios';

const getCategories = async (context ) => {
  context.commit('SET_CATEGORIES_LOADING', true );
  try {
    const response = await axios.get(`/api/admin/categories`, {
      params: {
        page: context.state.pagination.page
      }
    });

    // Vue.$swal(Vue.$t('swal.title.error'), 'biba', 'error');
    switch (response.data.status) {
      case 'error':
        Vue.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
        break;
      case 'success':
        context.commit('SET_CATEGORIES', response.data.categories);
        context.commit('SET_CATEGORIES_LAST_PAGE', response.data.categories.last_page);
        break;
    }
  } catch (e) {

  } finally {
    context.commit('SET_CATEGORIES_LOADING', false );
  }
};

export default {
  getCategories
}