import React from 'react'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-icons/font/bootstrap-icons.css'

function Sidebar() {
  return (
    <div className='container-fluid'>
        <div className='row'>
            <div className='bg-dark col auto col-md-3 min-vh-100'>
                <a className='text-decoration-none text-white d-flex align-itemcenter'>
                    <i className='fs-4 bi bi-speedometer'></i>
                    <span className='ms-1 fs-4'></span>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a href="#" class="nav-link active" aria-current="page">Active</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Link</a>
                        </li>
                        <li class="nav-item disabled">
                            <a href="#" class="nav-link">Disabled</a>
                        </li>
                    </ul>
                </a>
            </div>
        </div>
    </div>
  )
}

export default Sidebar