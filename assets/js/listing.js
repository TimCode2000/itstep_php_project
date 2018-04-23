var  personPerPage=20;
var  pages= [];
var currentPage=1;
var currentUsers=[];


var app = new Vue({
    el: '#UserList',
    data: {
        users: Persons,
        pages:pages,       
        currentPage:0,
        personPerPage:20,
        currentUsers:currentUsers,
        counter: 0        
    },
    methods: {
        colvoPages: function (){
           return Math.ceil(Persons.length /personPerPage);
        },
        pagess: function () {
            for (i = 1; i < app.colvoPages(); i++) {
                pages.push(i);
            }
             return pages;
        },
        getCurrentusers:function(){
          this.currentUsers=Persons.slice(this.currentPage, (this.currentPage + this.personPerPage));  
        console.log(this.currentUsers);
        },
        count: function(){
            
            if (this.currentPage>=this.colvoPages()){
              this.currentPage=0;                
            }
            else {
                 this.currentPage++;            
            } 
        }
    }, 
    computed:{
        //currentUsers:this.getCurrentusers();
    }
}) //Vue
pages=app.pagess();
app.currentUsers=app.getCurrentusers();
currentUsers=app.getCurrentusers();
