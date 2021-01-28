<template>
 <b-row>
   <b-col sm="12">
      <template v-slot:headerTitle>
        <h4 class="card-title">Files</h4>
      </template>
      <b-card-body class="iq-card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <!-- <th scope="col">
                <div class="checkbox text-center">
                  <input type="checkbox" class="checkbox-input">
                </div>
              </th> -->
              <th scope="col">File Name</th>
              <th scope="col">File Type</th>
              <th scope="col">Date</th>
              <th scope="col">State</th>
              <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item,index) in fileData" :key="index">
              <!-- <td>
                <div class="checkbox text-center">
                  <input type="checkbox" class="checkbox-input">
                </div>
              </td> -->
              <td>
                <i class="fas fa-lg fa-address-card mr-2" v-if="item.type === 'driver'" ></i>
                <i class="fas fa-lg fa-parking mr-2" v-if="item.type === 'parking'" ></i>
                <i class="fas fa-lg fa-car mr-2" v-if="item.type === 'vehicle'" ></i>
                <!-- <img class="rounded-circle img-fluid avatar-30 mr-2" :src="item.img" alt="profile"-->{{item.imgTitle}}
              </td>
              <td>{{item.heading}}</td>
              <td>
                {{item.date}}
              </td>
              <td><span class="badge badge-primary ml-3 text-white" v-if="item.status === 'verified'">{{ item.status }}</span>
              <span class="badge badge-danger ml-3 text-white" v-if="item.status !== 'verified'">{{ item.status }}</span>
              </td>
              <td>
                <div class="flex align-items-center list-user-action">
                  <a data-toggle="tooltip" data-placement="top" title="" data-original-title="Download" href="#"><i class="ri-download-line"></i></a>
                  <a data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" href="#"><i class="ri-delete-bin-line"></i></a>
                </div>
              </td>
            </tr>
            </tbody>
          </table>
        </div>

      </b-card-body>

   </b-col>
 </b-row>
</template>
<script>
import { socialvue } from '@/config/pluginInit'
export default {
  name: 'VerifyFile',
  mounted () {
    socialvue.index()
  },
  data () {
    return {
      fileData: [
        {
          img: require('../../../../assets/images/page-img/45.png'),
          imgTitle: 'post report',
          type:'driver',
          icon:'fa fa-id-card',
          heading: 'Document',
          date: 'Mar 12, 2020',
          status: 'verified'
        },
        {
          img: require('../../../../assets/images/page-img/45.png'),
          type:'parking',
          imgTitle: 'usages',
          heading: 'Document',
          date: ' Mar 18, 2020 ',
          status: 'verified'
        },
        {
          img: require('../../../../assets/images/page-img/45.png'),
          type:'vehicle',
          imgTitle: 'BMW-22',
          heading: 'Document',
          date: 'May 19, 2020',
          status: 'verified'
        },
        {
          img: require('../../../../assets/images/page-img/45.png'),
          type:'vehicle',
          imgTitle: 'Mercedes',
          heading: 'Document',
          date: 'May 19, 2020',
          status: 'verified'
        },
        {
          img: require('../../../../assets/images/page-img/45.png'),
          type:'vehicle',
          imgTitle: 'Images file',
          heading: 'Document',
          date: 'Mar 19, 2020',
          status: 'denied'
        },
      ],
      action: [
        {
          icon: 'ri-eye-fill mr-2',
          title: 'View'
        },
        {
          icon: 'ri-delete-bin-6-fill mr-2',
          title: 'Delete'
        },
        {
          icon: 'ri-pencil-fill mr-2',
          title: 'Edit'
        },
        {
          icon: 'ri-printer-fill mr-2',
          title: 'Print'
        },
        {
          icon: 'ri-file-download-fill mr-2',
          title: 'Download'
        }
      ]
    }
  }
}
</script>
