import React, { useEffect } from "react";
import Modal from "@/Components/Modal";
import { useForm } from "@inertiajs/react";
import Button from "@/Components/Button";
import FormInput from "@/Components/FormInput";

import { isEmpty } from "lodash";

export default function FormModal(props) {
    const { modalState } = props
    const { data, setData, post, put, processing, errors, reset, clearErrors } = useForm({
        name: '',
        address: '',
        phonenumber: '',
        emails: '',
    })

    const handleOnChange = (event) => {
        setData(event.target.name, event.target.type === 'checkbox' ? (event.target.checked ? 1 : 0) : event.target.value);
    }

    const handleReset = () => {
        modalState.setData(null)
        reset()
        clearErrors()
    }

    const handleClose = () => {
        handleReset()
        modalState.toggle()
    }

    const handleSubmit = () => {
        const supplier = modalState.data
        if(supplier !== null) {
            put(route('supplier.update', supplier), {
                onSuccess: () => handleClose(),
            })
            return
        } 
        post(route('supplier.store'), {
            onSuccess: () => handleClose()
        })
    }

    useEffect(() => {
        const supplier = modalState.data
        if (isEmpty(supplier) === false) {
            setData({
                name: supplier.name,
                address: supplier.address,
                phonenumber:supplier.phonenumber,
                emails:supplier.emails,
            })
            return 
        }
    }, [modalState])

    return (
        <Modal
            isOpen={modalState.isOpen}
            toggle={handleClose}
            title={"Supplier"}
        >
            <FormInput
                name="name"
                value={data.name}
                onChange={handleOnChange}
                label="Nama"
                error={errors.name}
            />
            <FormInput
                name="address"
                value={data.address}
                onChange={handleOnChange}
                label="Alamat"
                error={errors.address}
            />
            <FormInput
                name="phonenumber"
                value={data.phonenumber}
                onChange={handleOnChange}
                label="No Telphone"
                error={errors.phonenumber}
            />
            <FormInput
                type="email"
                name="emails"
                value={data.emails}
                onChange={handleOnChange}
                label="Email"
                error={errors.emails}
            />
            <div className="flex items-center">
                <Button
                    onClick={handleSubmit}
                    processing={processing} 
                >
                    Simpan
                </Button>
                <Button
                    onClick={handleClose}
                    type="secondary"
                >
                    Batal
                </Button>
            </div>
        </Modal>
    )
}