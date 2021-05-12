<template src="./TableSearch.htm"></template>

<script>
  export default {
    name: 'table-search',
    props: {
      searchFields: {
        type: Array,
        required: true
      },
      searchFieldDefaultIndex: {
        type: Number,
        default: 0
      }
    },
    data: function () {
      return {
        searchField: this.searchFields[this.searchFieldDefaultIndex],
        paramsString: '',
        searchDate: '',
        searchDateMax: '',
        searchValue: '',
        selectedOption: ''
      }
    },

    watch: {

      searchDate: function (val) {
        if (!val)
          return '';
        this.searchDateMax = moment(val).add(1, 'days').format('YYYY-MM-DD');
        this.searchValue = moment(val).format('YYYY-MM-DD');
      },

      searchValue: function (val) {
        this.setParamsString(val);
      },
      selectedOption: function (val) {
        this.setParamsString('');
      }
    },

    methods: {
      async search() {
        this.$emit('search', {params: this.paramsString})
      },

      clearSearch() {
        this.selectedOption = '';
        this.searchDateMax = '';
        this.searchValue = '';
        this.searchDate = '';
        this.paramsString = '';
        this.search();
      },

      openDatepicker() {
        if (this.searchField.type === 'date') {
          this.$refs.searchDatepicker.showCalendar();
        }
      },

      setSearchField(field) {
        this.searchField = field;
        this.setParamsString(this.searchValue);
      },

      setParamsString(val) {
        let params = '';
        if (this.searchField.type === 'text') {
          params = this.searchField.field + '=%' + val + '%';
        } else if (this.searchField.type === 'number') {
          params = this.searchField.field + '=' + val;
        } else if (this.searchField.type === 'date') {
          params = this.searchField.field + '[]=(ge)' + val + '&' + this.searchField.field + '[]=(le)' + this.searchDateMax;
        } else if (this.searchField.type === 'select' && this.selectedOption) {
          params = this.searchField.field + '=' + this.selectedOption;
        }

        this.paramsString = encodeURI(params);
      }
    }
  }
</script>