import debounce from '../debounce';

export default {
  methods: {
    paginationScroll: debounce((vm, element, callback = 'loadData') => {
      let atTheBottom = false;
      let isDocument = element === document;
      let scrollTop =  isDocument ? document.documentElement.scrollTop : element.scrollTop;
      let viewportHeight = isDocument ? window.innerHeight : element.offsetHeight;
      let totalHeight = isDocument ? $(element).height() : element.scrollHeight;

      atTheBottom = scrollTop + viewportHeight === totalHeight;

      if (atTheBottom && vm.pagination.page !== vm.pagination.last_page) {
        vm.pagination.page++;
        vm[callback]();
      }
    }, 300)
  }
}