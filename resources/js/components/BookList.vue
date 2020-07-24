<template>
    <div>
        <div>
            <router-link v-if="isLogged" to="/edit" class="btn btn-secondary">Добавить книгу</router-link>
            <router-link v-if="isLogged" to="/tags" class="btn btn-secondary">Теги</router-link>
        </div>
        <hr>
        <div class="row mb-1">
            <div class="col-6">

                <form @submit.prevent="search">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" v-model="searchValue">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">искать</button>
                        </div>
                    </div>
                </form>

            </div>
            <div class="col-6">
                <multiselect v-model="tag"
                             :options="tags"
                             :searchable="false"
                             :close-on-select="false"
                             label="name"
                             track-by="id"
                             placeholder="Выбрать"
                             :multiple="true"
                             @input="tagSelected"
                ></multiselect>
            </div>
        </div>
        <table class="table">
            <tr v-for="(item, index) in items" :key="index">
                <td>
                    <img class="book-list-image" v-if="item.image" :src="`/images/${item.image}`" onerror="this.src = '/images/noimg.jpg'">
                </td>
                <td><router-link :to="`/book/${item.id}`">{{ item.name }}</router-link></td>
                <td>{{ item.authors }}</td>
                <td>{{ item.year }}</td>
                <td>
                    <span class="badge badge-secondary ml-1" v-for="tag in item.tags">{{ tag.name }}</span>
                </td>

                <td v-if="isLogged">
                    <button class="btn btn-danger btn-sm" @click="remove(item, index)"><i class="fa fa-trash"></i></button>
                    <router-link :to="`/edit/${item.id}`" class="btn btn-secondary btn-sm"><i class="fa fa-edit"></i></router-link>
                </td>
            </tr>
        </table>

        <nav v-if="pageCount">
            <paginate
                    :page-count="pageCount"
                    :page-range="3"
                    :margin-pages="2"
                    :click-handler="navigate"
                    :container-class="'pagination'"
                    :page-class="'page-item'"
                    :page-link-class="'page-link'"
                    :prev-link-class="'page-link'"
                    :next-link-class="'page-link'"
                    v-model="page"
            >
            </paginate>
        </nav>
    </div>
</template>

<script>

	import Paginate from 'vuejs-paginate';
	import Multiselect from 'vue-multiselect';
	import 'vue-multiselect/dist/vue-multiselect.min.css';

    export default {

    	components: {
			Paginate,
            Multiselect
        },

    	data(){
    	    return {
    	    	items: [],
                page: 1,
                pageCount: 0,
                isLogged: false,
                tags: [],
                tag: '',
				searchValue: ''
            }
        },

        created() {
			if(this.$route.params.page) this.page = parseInt(this.$route.params.page);
			this.getItems();
			this.isLogged = window.api_token;
        },

        methods: {

    		navigate(page = 1){
                this.$router.push('/page-' + page);
				this.getItems();
            },

            getItems(params = {}){
				axios.get('/api/books', {params: {page: this.page, ...params}}).then( res => { // not await for not block
					this.items = res.data.items.data;
					this.tags = res.data.tags;
					if(res.data.items.total) this.pageCount = Math.floor(res.data.items.total / res.data.items.per_page) + 1;
					else this.pageCount = 0;
				});
            },

            remove(item, index){
    			if(!confirm('Удалить ' + item.name + '?')) return;
                axios.delete('/api/book/' + item.id);
                this.items.splice(index, 1);
            },

			tagSelected(){
                if(this.tag){
					this.getItems({ tag: this.tag.map( item => item.id) });
					this.searchValue = '';
                }
                else this.getItems();
            },

            search(){
				if(this.searchValue){
					this.getItems({ search: this.searchValue });
					this.tag = '';
                }
				else this.getItems();
            }
        }
    }
</script>

<style>
    .book-list-image {
        max-height: 30px;
    }
</style>
