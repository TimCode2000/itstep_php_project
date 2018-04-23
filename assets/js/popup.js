var interests = [];
var persons = [];

function ViewUser() {
  var popup = document.getElementById("myPopup");
  popup.classList.toggle("show");
}

function addData(JSONusers, JSONinterests) {
  if (JSONusers === undefined && JSONinterests === undefined) {
    document.write("undefined")
  } else {
    persons = JSONusers;
    interests = JSONinterests;
  }
}

var popup = new Vue({
  el: "#popup",
  data: {
    id: 0,
    firstName: "",
    lastName: "",
    age: 0,
    phone: 0,
    active: "",
    interests: [],
    fullInterests: []
  },
  methods: {
    createPerson: function (id) {
      this.id = persons[id].id;
      this.firstName = persons[id].firstName;
      this.lastName = persons[id].lastName;
      this.age = persons[id].age;
      this.phone = persons[id].phone;
      this.active = persons[id].active;
      if(persons[id].active==1){
        this.active = "Yes";
      }
      else this.active = "No";
      this.interests = persons[id].interests;
      if (this.fullInterests.length == 0) {
        for (var i = 0; i < this.interests.length; i++) {
          this.fullInterests.push(interests[i].description);
        }
      }
    }
  }
})
