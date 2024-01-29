<!-- src/components/Table.vue -->

<template>
  <div>
    <table class="min-w-full border border-gray-300">
      <!-- Table headers -->
      <thead>
      <tr>
        <th class="py-2 px-4 border-b text-left">ID</th>
        <th class="py-2 px-4 border-b text-left">Name</th>
        <th class="py-2 px-4 border-b text-left">Last Name</th>
        <th class="py-2 px-4 border-b text-left">Email</th>
        <th class="py-2 px-4 border-b text-left">Status</th>
        <th class="py-2 px-4 border-b text-left">Actions</th>
        <!-- Add more columns as needed -->
      </tr>
      </thead>

      <!-- Table body -->
      <tbody>
      <tr v-for="record in data" :key="record.id">
        <td class="py-2 px-4 border-b">{{ record.id }}</td>
        <td class="py-2 px-4 border-b">{{ record.name }}</td>
        <td class="py-2 px-4 border-b">{{ record.last_name }}</td>
        <td class="py-2 px-4 border-b">{{ record.email }}</td>
        <td class="py-2 px-4 border-b">{{ record.status === 1 ? 'Active' : 'Inactive' }}</td>
        <td class="py-2 px-4 border-b">
          <button class="bg-blue-500 text-white px-4 py-2 rounded" @click="$emit('show-subscriber', record.email)">View</button>
        </td>
        <!-- Add more columns as needed -->
      </tr>
      </tbody>
    </table>

    <!-- Pagination controls -->
    <div v-if="meta.total_pages > 1" class="mt-4">
      <button
          v-if="meta.page > 1"
          @click="changePage(parseInt(meta.page) - 1)"
          class="px-3 py-2 mx-1 border rounded hover:bg-gray-200"
      >
        Previous
      </button>
      <template v-if="meta.total_pages <= 10">
        <!-- Display all pages if there are 10 or fewer pages -->
        <button
            v-for="pageNumber in meta.total_pages"
            :key="pageNumber"
            @click="changePage(pageNumber)"
            class="px-3 py-2 mx-1 border rounded hover:bg-gray-200"
            :class="{ 'bg-gray-200': pageNumber === parseInt(meta.page) }"
        >
          {{ pageNumber }}
        </button>
      </template>
      <template v-else>
        <!-- Display ellipsis if there are more than 10 pages -->
        <button
            v-for="pageNumber in displayedPages"
            :key="pageNumber"
            @click="changePage(pageNumber)"
            class="px-3 py-2 mx-1 border rounded hover:bg-gray-200"
            :class="{ 'bg-gray-300': pageNumber === parseInt(meta.page) }"
        >
          {{ pageNumber }}
        </button>
        <button
            v-if="meta.page < meta.total_pages"
            @click="changePage(parseInt(meta.page) + 1)"
            class="px-3 py-2 mx-1 border rounded hover:bg-gray-200"
        >
          Next
        </button>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Array,
      required: true,
    },
    meta: {
      type: Object,
      default: {
        total: 0,
        total_pages: 1,
        page: 1,
        limit: 10,
        per_page: 10,
      },
    },
  },
  computed: {
    displayedPages() {
      const currentPage = this.meta.page;
      const lastPage = this.meta.total_pages;
      const visiblePages = 10;

      if (currentPage <= visiblePages - 5) {
        return Array.from({ length: visiblePages - 2 }, (_, i) => i + 1);
      } else if (currentPage >= lastPage - visiblePages + 4) {
        return Array.from({ length: visiblePages - 2 }, (_, i) => lastPage - visiblePages + 3 + i);
      } else {
        return Array.from({ length: visiblePages - 4 }, (_, i) => currentPage - 1 + i);
      }
    },

  },
  methods: {
    changePage(newPage) {
      this.$emit('page-change', newPage);
    },
  },
};
</script>

<style scoped>
/* Add custom styling here if needed */
</style>
