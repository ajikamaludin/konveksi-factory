import React, { useEffect } from "react";
import { Head, useForm } from "@inertiajs/react";
import { HiLockClosed, HiXCircle } from "react-icons/hi";
import { isEmpty } from "lodash";
import { useModalState } from "@/hooks";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import Button from "@/Components/Button";
import FormInput from "@/Components/FormInput";
import FormInputDate from "@/Components/FormInputDate";
import SupplierSelectionInput from "../Supplier/SelectedInput";
import CompositionSelectionInput from "../Composition/SelectedInput";
import FormModal from "./FormModal";

export default function Form(props) {
    const { fabric } = props;

    const { data, setData, post, put, processing, errors } = useForm({
        name: "",
        order_date: "",
        letter_number: "",
        composisi_id: "",
        setting_size: "",
        supplier_id: "",
        code_lot: "",
        items: [],
    });

    const handleOnChange = (event) => {
        setData(
            event.target.name,
            event.target.type === "checkbox"
                ? event.target.checked
                    ? 1
                    : 0
                : event.target.value
        );
    };

    const formItemModal = useModalState();

    const onItemAdd = (newitem) => {
        const isExists = data.items.findIndex((i) => i.code === newitem.code);
        if (isExists != -1) {
            let items = data.items.map((detail, index) => {
                if (isExists === index) {
                    return {
                        ...detail,
                        detail_fabrics: detail.detail_fabrics.concat(
                            newitem.detail_fabrics
                        ),
                    };
                }
                return detail;
            });
            setData({ ...data, items });
        } else {
            setData("items", data.items.concat(newitem));
        }
    };

    const onItemRemove = (index) => {
        setData(
            "items",
            data.items.filter((_, i) => i !== index)
        );
    };

    const handleSubmit = () => {
        if (isEmpty(fabric) === false) {
            put(route("fabric.update", fabric));
            return;
        }
        post(route("fabric.store"));
    };

    useEffect(() => {
        if (isEmpty(fabric) === false) {
            // console.log(fabric);
            setData({
                name: fabric.name,
                order_date: fabric.order_date,
                letter_number: fabric.letter_number,
                composisi_id: fabric.composisi_id,
                setting_size: fabric.setting_size,
                supplier_id: fabric.supplier_id,
                items: fabric.fabric_items,
            });
        }
    }, [fabric]);
   
    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            flash={props.flash}
            page={"Dashboard"}
            action={"Kain"}
        >
            <Head title={"Kain"} />

            <div>
                <div className="mx-auto sm:px-6 lg:px-8">
                    <div className="overflow-hidden p-4 shadow-sm sm:rounded-lg bg-white dark:bg-gray-800 flex flex-col ">
                        <div className="text-xl font-bold mb-4">Kain</div>
                        <FormInput
                            name="name"
                            value={data.name}
                            onChange={handleOnChange}
                            label="Nama"
                            error={errors.name}
                        />
                        <FormInputDate
                            name="order_date"
                            selected={data.order_date}
                            onChange={(date) => setData("order_date", date)}
                            label="Tanggal"
                            error={errors.order_date}
                        />
                        <div className="mb-2">
                            <SupplierSelectionInput
                                label="Supplier"
                                itemSelected={data.supplier_id}
                                onItemSelected={(id) =>
                                    setData("supplier_id", id)
                                }
                                error={errors.supplier_id}
                            />
                        </div>
                        <FormInput
                            name="letter_number"
                            value={data.letter_number}
                            onChange={handleOnChange}
                            label="Nomor Surat Jalan"
                            error={errors.letter_number}
                        />

                        <div className="mb-2">
                            <CompositionSelectionInput
                                label="Komposisi"
                                itemSelected={data.composisi_id}
                                onItemSelected={(id) =>
                                    setData("composisi_id", id)
                                }
                                error={errors.composisi_id}
                            />
                        </div>

                        <FormInput
                            name="setting_size"
                            value={data.setting_size}
                            onChange={handleOnChange}
                            label="Setting Kain"
                            error={errors.setting_size}
                        />

                        <label>Item</label>
                        <div className="w-full flex flex-col border-2 rounded-lg p-2">
                            <div className="mb-2">
                                <button
                                    type="button"
                                    className="px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300"
                                    onClick={formItemModal.toggle}
                                >
                                    Tambah
                                </button>
                            </div>
                            <table className="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-4">
                                <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" className="py-3 px-6">
                                            Kode Lot
                                        </th>
                                        <th scope="col" className="py-3 px-6">
                                            Quantity
                                        </th>
                                        <th scope="col" className="py-3 px-6" />
                                    </tr>
                                </thead>
                                <tbody>
                                    {data.items.map((item, index) => (
                                        <tr
                                            className="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                            key={index}
                                        >
                                            <td
                                                scope="row"
                                                className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                            >
                                                {item.code}
                                            </td>
                                            <td className="py-4 px-6">
                                                {item.detail_fabrics.map(
                                                    (detail, i) => (
                                                        <div key={i}>
                                                            {`${detail.qty} kg`}
                                                        </div>
                                                    )
                                                )}
                                            </td>
                                            <td>
                                                {
                                                    item.detail_fabrics.reduce((sum, detail) => sum += detail.result_qty, 0) == 0 || item.detail_fabrics.result_qty==undefined ?(
                                                        <HiXCircle
                                                            className="w-5 h-5 text-red-600"
                                                            onClick={() => onItemRemove(index)}
                                                        />
                                                    ):(<HiLockClosed className="w-5 h-5 text-red-600"/>)
                                                }

                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        <div className="mt-10">
                            <Button
                                onClick={handleSubmit}
                                processing={processing}
                            >
                                Simpan
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
            <FormModal modalState={formItemModal} onItemAdd={onItemAdd} />
        </AuthenticatedLayout>
    );
}
