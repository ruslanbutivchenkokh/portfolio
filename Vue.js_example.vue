<template>
	<div class="box">
		<div class="columns">
			<div class="column is-6" v-if="projectCreateStatus !== 'edit'">
				<input 
					type="text" 
					class="input" 
					placeholder="Project name"
					v-model="projectTitle"
				>
			</div>

			<div class="column is-6" v-if="projectCreateStatus === 'edit'">
				<h2>{{ projectTitle }}</h2>
			</div>

			<div class="column has-text-centered" v-if="projectCreateStatus === 'create'">
				<button class="button is-primary"
					:disabled="!projectTitle"
					@click="
					projectCreateStatus = 'edit',
					addProjectTitle(projectTitle),
					createdTitle()
				">Save</button>
			</div>

			<div class="column has-text-centered" v-if="projectCreateStatus === 'edit'">
				<button class="button is-link" 
					@click="projectCreateStatus = 'update'"
				>Edit</button>
			</div>
			
			<div class="column has-text-centered" v-if="projectCreateStatus === 'update'">
				<button class="button is-warning" 
					@click="
						projectCreateStatus = 'edit',
						editProjectTitle(projectTitle)
					"
				>Update</button>
			</div>
		</div>
	</div>
</template>

<script>
import { v4 as uuidv4 } from 'uuid';

export default {
	name: 'Project create title',
	data(){
		return {
			projectCreateStatus: 'create',
			projectTitle: '',
		}
	},

	methods: {
		createdTitle(){
			this.$emit('createdTitle')
		},

		addProjectTitle(projectTitle) {
			let projectID = uuidv4();
			this.$store.dispatch('createProject', {projectTitle, projectID})
		},

		editProjectTitle(projectTitle) {
			let projectID = this.$store.state.projectID
			projectTitle = this.projectTitle
			this.$store.dispatch('updateProject', {projectTitle, projectID})
		},
	}
}
</script>