<!-- src/App.vue -->

<template>
  <div class="min-h-screen flex mt-10 justify-center">
    <div class="w-11/12">
      <h1 class="text-4xl font-bold mb-6">Subscribers</h1>
      <button @click="openModal" class="mb-4 bg-blue-500 text-white px-4 py-2 rounded">Add Subscriber</button>
      <Table :data="records" :meta="meta" @page-change="updatePage" @show-subscriber="showSubscriber" />

      <!-- Modal -->
      <div v-if="showAddModal" class="fixed inset-0 z-10 overflow-y-auto">
        <div class="absolute inset-0 bg-black opacity-50 z-20"></div>
        <div class="flex items-center justify-center min-h-screen">
          <div class="bg-white p-6 rounded shadow-md w-1/2 relative z-30">
            <h2 class="text-2xl font-bold mb-4">Add New Subscriber</h2>
            <form @submit.prevent="addRecord">
              <label class="block mb-2">Name:</label>
              <input v-model="newRecord.name" class="border p-2 w-full" />

              <label class="block mt-4 mb-2">Last Name:</label>
              <input v-model="newRecord.last_name" class="border p-2 w-full" />

              <label class="block mt-4 mb-2">Email:</label>
              <input v-model="newRecord.email" type="email" class="border p-2 w-full" />

              <label class="block mt-4 mb-2">Status:</label>
              <select v-model.number="newRecord.status" class="border p-2 w-full">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>

              <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert" v-show="error">
                <strong class="font-bold">Error! </strong>
                <span class="block sm:inline">{{error}}.</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="error = null">
                  <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                  </svg>
                </span>
              </div>

              <div class="mt-4">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Add</button>
                <button @click="closeModal" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div v-if="showViewModal" class="fixed inset-0 z-10 overflow-y-auto">
        <div class="absolute inset-0 bg-black opacity-50 z-20"></div>
        <div class="flex items-center justify-center min-h-screen">
          <div class="bg-white p-6 rounded shadow-md w-1/2 relative z-30">
            <h2 class="text-2xl font-bold mb-4">Subscriber</h2>
            <label class="block mb-2">Name: {{subscriber.name}}</label>
            <label class="block mb-2">Last Name: {{subscriber.last_name}}</label>
            <label class="block mb-2">Email: {{subscriber.email}}</label>
            <label class="block mb-2">Status: <span v-if="subscriber.status !== null"> {{subscriber.status === 1 ? "Active" : "Inactive"}}</span></label>

            <div class="mt-4">
              <button @click="showViewModal = false" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Table from './components/Table.vue';
import axios from 'axios';

export default {
  components: {
    Table,
  },
  data() {
    return {
      records: [],
      showAddModal: false,
      showViewModal: false,
      error: '',
      subscriber: {
        name: '',
        last_name: '',
        email: '',
        status: '',
      },
      meta: {
        total: 0,
        page: 1,
        limit: 10,
      },
      newRecord: {
        name: '',
        last_name: '',
        email: '',
        status: null,
      },
    };
  },
  mounted() {
    this.fetchRecords();
  },
  methods: {
    async fetchRecords() {
      try {
        const response = await axios.get(`${import.meta.env.VITE_BASE_URL}/subscribers`, {
          params: {
            page: this.meta.page,
            limit: this.meta.limit,
          },
        });
        this.records = response.data.data;
        this.meta = response.data.meta;
      } catch (error) {
        console.error('Error fetching records:', error);
      }
    },
    openModal() {
      this.showAddModal = true;
    },
    closeModal() {
      this.showAddModal = false;
      this.resetForm();
    },
    resetForm() {
      this.newRecord = {
        name: '',
        last_name: '',
        email: '',
        status: null,
      };

      this.error = '';
    },
    async addRecord() {

      try {
        await axios.post(`${import.meta.env.VITE_BASE_URL}/subscribe`, this.newRecord);
        this.fetchRecords();

        this.closeModal();
      } catch (error) {

        this.$nextTick(() => {
          this.error = error.response.data.error;
        });
      }
    },
    async showSubscriber(email) {
      try {
        const response = await axios.get(`${import.meta.env.VITE_BASE_URL}/subscriber`, {
          params: {
            email: email,
          },
        });

        this.subscriber = response.data.data;

        this.showViewModal = true;
      } catch (error) {
        console.error('Error fetching subscriber:', error);
      }
    },
    async updatePage(pageNumber) {
      this.meta.page = pageNumber;
      await this.fetchRecords();
    },
  },
};
</script>

<style>
/* Add global styling here if needed */
</style>
