<template>

        <form @submit.prevent="save" novalidate>



            <div class="row">
                <div class="col-md-8 col-sm-12 border-right">
                    <div class="form-group">
                        <label>Название*</label>
                        <input class="form-control" v-model="item.name" :class="{'is-invalid': getError('name')}"  required>
                        <div class="invalid-feedback" v-show="!getError('name')">Обязательное поле</div>
                        <div class="invalid-feedback" v-show="getError('name')">{{getError('name')}}</div>
                    </div>
                    <div class="form-group">
                        <label>Авторы</label>
                        <input class="form-control" v-model="item.authors">
                    </div>
                    <div class="form-group">
                        <label>Год</label>
                        <input class="form-control" v-model="item.year">
                    </div>

                    <div>
                        <label class="typo__label">Теги</label>
                        <multiselect v-model="item.tags"
                                     :options="tags"
                                     :searchable="false"
                                     :close-on-select="false"
                                     label="name"
                                     track-by="id"
                                     placeholder="Выбрать"
                                     :multiple="true"
                        ></multiselect>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div>
                        <div class="img-thumbnail" style="width: 250px;height: 250px;">
                            <img v-if="item.image"
                                 :src="`/images/${item.image}`"
                                 onerror="this.src = '/images/noimg.jpg'"
                            >
                        </div>
                        <div class="custom-file mt-3">
                            <input type="file" class="custom-file-input" @change="setFile" >

                            <label class="custom-file-label"><i class="upload-icon fa fa-upload"></i>{{fileName}}</label>
                        </div>
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
                tags: [],
				errors: {},
				isSaving: false,
                saved: false,
				fileName: '',
				file: {},
			}
		},

		async created() {
			let id = this.$route.params.id ? this.$route.params.id : 0;
			let res = await axios.get('/api/edit/' + id);
			this.item = res.data.item;
			this.tags = res.data.tags;
		},

		methods: {

			setFile(e){
				this.fileName = e.target.files[0].name;
				this.file = e.target.files[0];
			},

			async save() {


				this.$el.classList.add('was-validated');
				if (this.$el.checkValidity() === false) return;

				this.errors = {};
				this.isSaving = true;

				let response;
				response = await axios.post('/api/book/save', this.toFormData({
					...this.item,
					file: this.file
				}))
					.catch(res => response = res.response);

				this.isSaving = false;

				if (!this.hasErrors(response)) {
					this.saved = true;
					setTimeout( () => this.saved = false, 2000);
					if (response.data.item && !this.item.id) this.$router.push({path: '/edit/' + response.data.item.id});
					this.item.image = response.data.item.image;
				}

			},


			hasErrors(response) {
				if (response.status == 422 && typeof response.data == 'object' && response.data.errors) {
					this.errors = this.normalizeErrors(response.data.errors);
					this.$el.classList.remove('was-validated');
					return true;
				}
			},

			getError(key) {
				return typeof this.errors[key] != 'undefined' ? this.errors[key] : false;
			},

			toFormData(...args){
				const form = new FormData();
				for(let [key, val] of Object.entries(args[0])){
					if(Array.isArray(val)){
						for(let item of val) form.append(key + '[]', item.id);
					}else {
						form.append(key, val);
                    }
				}
				return form;
			},

			normalizeErrors(errors){
				let out = {};
				Object.entries(errors).reduce( (acc, item) => acc[ item[0] ] = item[1].join(', '), out);
				return out;
			}
		}
    }
</script>

<style scoped>
    .img-thumbnail img {
        max-width: 100%;
        max-height: 100%;
    }
    .custom-file-label::after {
        content: "Выбрать" !important;
    }

    .upload-icon {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 4;
        display: block;
        height: calc(calc(2.25rem + 2px) - 1px * 2);
        padding: .375rem .75rem;
        line-height: 1.5;
        color: #495057;
        background-color: #e9ecef;
        border-left: 1px solid #ced4da;
        border-radius: 0 .25rem .25rem 0;
    }

    .fa-upload {
        display: none;
    }

    @media screen and (max-width: 1024px){
        .fa-upload {
            display: inline-block;
        }
        .custom-file-label::after {
            content: "" !important;
        }
    }

</style>