<template>

    <form @submit.prevent="save" novalidate>



        <div class="row">
            <div class="col-md-8 col-sm-12 border-right">
                <div class="form-group">
                    <label>Название*</label>
                    <input class="form-control" v-model="item.name" required>
                </div>
            </div>
        </div>
        <hr>
        <div>
            <button class="btn btn-secondary" type="submit" :disabled="isSaving">Сохранить</button>
        </div>
        <div class="alert alert-success" v-if="saved">Сохранено</div>
    </form>

</template>

<script>

	import Multiselect from 'vue-multiselect';
	import 'vue-multiselect/dist/vue-multiselect.min.css';

	export default {

		components: {
			Multiselect
		},

		data(){
			return {
				item: {},
				isSaving: false,
				saved: false,
			}
		},

		async created() {
			let id = this.$route.params.id ? this.$route.params.id : 0;
			let res = await axios.get('/api/tag/' + id);
			this.item = res.data.item;
		},

		methods: {

			setFile(e){
				this.fileName = e.target.files[0].name;
				this.file = e.target.files[0];
			},

			async save() {


				this.$el.classList.add('was-validated');
				if (this.$el.checkValidity() === false) return;

				this.isSaving = true;

				let response;
				response = await axios.post('/api/tag/save', this.item);

				this.isSaving = false;

					this.saved = true;
					setTimeout( () => this.saved = false, 2000);
					if (response.data.item && !this.item.id) this.$router.push({path: '/tag/' + response.data.item.id});

			},
		}
	}
</script>

