import React, { useState, useEffect } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head,router } from '@inertiajs/react';
import { formatIDR } from '@/utils';
import Button from '@/Components/Button';

export default function Index(props) {
    const { _production, hourline, target, operator,hpp,hasil } = props
    const [classover,setClassOver]=useState();
    const [fullwith,setFullwith]=useState();
    const [isfull,setFull]=useState(false)
    const handlefull=()=>{
        if(isfull==false){
            setClassOver("overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 p-4 w-full md:inset-0 h-modal md:h-full justify-center items-center bg-opacity-50 dark:bg-opacity-90 bg-gray-900 dark:bg-gray-900")
            setFullwith("relative w-full h-full md:h-auto");
            setFull(true);
        }else{
            setClassOver("");
            setFullwith("");
            setFull(false);
        }
        
    }
    useEffect(()=>{
        setTimeout(()=>
        router.get(
            route(route().current())
        )
        ,30000);
    },[_production])
  

    return (
        
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            flash={props.flash}
            page={'Dashboard'}
            action={'Tv'}
        >
            <Head title="Tv" />
        
            <div className={classover}>
                <div className={fullwith+" mx-auto sm:px-6 lg:px-8"}>
                    <div className="p-6 overflow-hidden shadow-sm sm:rounded-lg bg-white space-y-6 min-h-screen">
                        <div className='grid grid-cols-4 text-center'>
                            <div className='border-r-2'>
                                <div className='mb-2'>{_production.name}</div>
                            </div>
                            <div className='border-r-2'>
                                <div className='mb-2'>{_production.active_line}</div>
                            </div>
                            <div className='border-r-2 mt-1'>
                                <div div className='mb-2'>PO {formatIDR(_production.total)}</div>
                            </div>
                            <div className='border-r-2 mt-1'>
                                <div div className='mb-2'>Jam {hourline}</div>
                            </div>
                        </div>
                        <div className='grid grid-cols-3 text-center'>
                            <div className='border-r-2'>
                                <div div className='mb-2'>Target</div>
                                <div div className='mb-2'>{target}</div>
                            </div>
                            <div className='border-r-2'>
                                <div div className='mb-2'>Hasil</div>
                                <div div className='mb-2'>{hasil}</div>
                            </div>
                            <div className='border-r-2'>
                            <div div className='mb-2'>Sisa PO</div>
                                <div div className='mb-2'>{formatIDR(_production.left)}</div>
                            </div>
                        </div>
                        <div className='grid grid-cols-3 text-center'>
                            <div className='border-r-2'>
                                <div div className='mb-2'>Operator</div>
                                <div div className='mb-2'>{operator}</div>
                            </div>
                            <div className='border-r-2'>
                                <div div className='mb-2'>Perkiraan</div>
                                <div div className='mb-2'>{target}</div>
                            </div>
                            <div className='border-r-2'>
                                <div div className='mb-2'>HPP</div>
                                <div div className='mb-2'>{formatIDR(hpp)}</div>
                            </div>
                        </div>
                        <div className="flex items-center">
                            <Button onClick={handlefull}>{isfull? 'Mini Screen':'Full Screen'}</Button>
                        </div>
                    </div>
                </div>
            </div>
           
        </AuthenticatedLayout>
      
    )
}