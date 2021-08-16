<template>
<h1> this is from view</h1>
</template>



<script>
    export default {
     
        data(){
            return {
                val: [],
                form: {
                    name: '',
                    relation: '',
                    email_phone: '',
                    type: '',
                    inmate_id: '',
                    not_working: true
                }
            }
        },
        created() {
            this.fetchContactList();
        },
        methods:{
            fetchContactList() {
                axios.get('viewcontacts').then((res) => {
                    this.val = res.data;
                });
            },
            createContact(){
                this.$http.post('/inmatecontact_create',{data:this.form})
                    .then((resp)=>{
                        console.log(resp);

                    })  
            },
            deleteContact(id){
                 axios.delete('deletecontact/' + id)
                    .then((res) => {
                        this.fetchContactList()
                    })
                    .catch((err) => console.error(err));
                }
            },

        watch:{
            content(){
                if(this.name.length > 3 || this.relation.length >3 || this.email_phone.length > 6 || this.type.length > 4 || this.inmate_id.length > 0)
                    this.not_working=false
                else
                    this.not_working=true
            }
        }
    }
</script>

