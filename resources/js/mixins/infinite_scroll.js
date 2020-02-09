import debounce from '../debounce';

export default {
  methods: {
    onScroll: function() {
      this.paginationScroll(this);
    },

    paginationScroll: debounce(vm => {
      let atTheBottom = false;
      let scrollTop = document.documentElement.scrollTop;
      let viewportHeight = window.innerHeight;
      let totalHeight = $(document).height();

      atTheBottom = scrollTop + viewportHeight === totalHeight;

      if (atTheBottom && vm.pagination.page !== vm.pagination.last_page) {
        vm.pagination.page++;
        vm.loadData();
      }
    }, 300)
  }
}